<?php

namespace App\Services;

use App\Mail\BookingCancelledMail;
use App\Mail\BookingConfirmedMail;
use App\Models\Booking;
use App\Models\Cancellation;
use App\Models\Payment;
use App\Models\Showtime;
use App\Support\BookingStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Seats held by pending or confirmed bookings for a showtime.
     */
    public function getUnavailableSeats(int $showtimeId): array
    {
        return Booking::where('showtime_id', $showtimeId)
            ->whereIn('status', [
                BookingStatus::PENDING,
                BookingStatus::CONFIRMED,
                BookingStatus::CANCELLATION_REQUESTED,
            ])
            ->pluck('seats')
            ->flatMap(fn (string $seats) => array_filter(array_map('trim', explode(',', $seats))))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return array<int, string> Conflicting seat identifiers
     */
    public function findSeatConflicts(Showtime $showtime, string $seatsCsv): array
    {
        $selected = array_filter(array_map('trim', explode(',', $seatsCsv)));
        $unavailable = $this->getUnavailableSeats($showtime->id);

        return array_values(array_intersect($selected, $unavailable));
    }

    public function createBooking(int $userId, int $showtimeId, string $seats, int $numberOfTickets): Booking
    {
        return DB::transaction(function () use ($userId, $showtimeId, $seats, $numberOfTickets) {
            $showtime = Showtime::lockForUpdate()->findOrFail($showtimeId);

            $conflicts = $this->findSeatConflicts($showtime, $seats);
            if ($conflicts !== []) {
                throw ValidationException::withMessages([
                    'seats' => ['The following seats are already taken: '.implode(', ', $conflicts)],
                ]);
            }

            if ($showtime->available_seats < $numberOfTickets) {
                throw ValidationException::withMessages([
                    'number_of_tickets' => ['Not enough seats available for this showtime.'],
                ]);
            }

            $selectedCount = count(array_filter(array_map('trim', explode(',', $seats))));
            if ($selectedCount !== $numberOfTickets) {
                throw ValidationException::withMessages([
                    'seats' => ['Number of selected seats must match the ticket count.'],
                ]);
            }

            return Booking::create([
                'user_id' => $userId,
                'showtime_id' => $showtimeId,
                'number_of_tickets' => $numberOfTickets,
                'seats' => $seats,
                'total_price' => $showtime->ticket_price * $numberOfTickets,
                'status' => 'pending',
            ]);
        });
    }

    /**
     * Mock payment confirmation — only stores safe payment metadata (no CVV, no full card number).
     */
    public function confirmPayment(Booking $booking, array $paymentInput = []): array
    {
        if ($booking->status === 'confirmed') {
            return [
                'success' => false,
                'message' => 'Booking already confirmed.',
                'booking' => $booking->load('showtime.movie', 'showtime.theatre'),
                'status' => 200,
            ];
        }

        if ($booking->status === 'cancelled') {
            return [
                'success' => false,
                'message' => 'This booking cannot be confirmed because it was cancelled.',
                'booking' => $booking->load('showtime.movie', 'showtime.theatre'),
                'status' => 422,
            ];
        }

        return DB::transaction(function () use ($booking, $paymentInput) {
            $booking = Booking::lockForUpdate()->findOrFail($booking->id);

            if ($booking->status === 'confirmed') {
                return [
                    'success' => false,
                    'message' => 'Booking already confirmed.',
                    'booking' => $booking->load('showtime.movie', 'showtime.theatre'),
                    'status' => 200,
                ];
            }

            $showtime = Showtime::lockForUpdate()->findOrFail($booking->showtime_id);

            if ($showtime->available_seats < $booking->number_of_tickets) {
                return [
                    'success' => false,
                    'message' => 'Not enough seats available to confirm this booking.',
                    'booking' => $booking->load('showtime.movie', 'showtime.theatre'),
                    'status' => 422,
                ];
            }

            $booking->update(['status' => 'confirmed']);

            $newAvailable = max(0, $showtime->available_seats - $booking->number_of_tickets);
            $showtime->update(['available_seats' => $newAvailable]);

            $this->recordPayment($booking, $paymentInput);

            $booking = $booking->fresh()->load('showtime.movie', 'showtime.theatre', 'user');

            $this->sendBookingConfirmedEmail($booking);

            return [
                'success' => true,
                'message' => 'Payment confirmed and booking finalized',
                'booking' => $booking,
                'status' => 200,
            ];
        });
    }

    public function cancellationBlockReason(Booking $booking): ?string
    {
        $booking->loadMissing('showtime');

        if ($booking->status === BookingStatus::CANCELLED) {
            return 'Booking already cancelled.';
        }

        if ($booking->status === BookingStatus::CANCELLATION_REQUESTED) {
            return 'A cancellation request is already pending for this booking.';
        }

        if (! in_array($booking->status, [BookingStatus::PENDING, BookingStatus::CONFIRMED], true)) {
            return 'This booking cannot be cancelled.';
        }

        if (! $booking->showtime?->showtime) {
            return null;
        }

        $showtimeAt = Carbon::parse($booking->showtime->showtime);

        if ($showtimeAt->isPast()) {
            return 'This booking cannot be cancelled because the showtime has already passed.';
        }

        if (now()->gte($showtimeAt->copy()->subMinutes(30))) {
            return 'Cancellations are not allowed within 30 minutes of showtime.';
        }

        return null;
    }

    /**
     * Customer submits a cancellation request (admin must approve).
     */
    public function requestCancellation(Booking $booking, string $reason, ?string $comments = null): array
    {
        if ($blockReason = $this->cancellationBlockReason($booking)) {
            return [
                'success' => false,
                'message' => $blockReason,
                'status' => 422,
            ];
        }

        $reasonText = $reason.($comments ? ' - '.$comments : '');

        return DB::transaction(function () use ($booking, $reasonText) {
            $booking = Booking::lockForUpdate()->findOrFail($booking->id);

            if ($booking->status === BookingStatus::CANCELLATION_REQUESTED) {
                return [
                    'success' => false,
                    'message' => 'A cancellation request is already pending for this booking.',
                    'status' => 422,
                ];
            }

            $originalAmount = $booking->total_price;
            $refundAmount = $originalAmount * 0.5;
            $cancellationFee = $originalAmount * 0.5;

            $booking->update(['status' => BookingStatus::CANCELLATION_REQUESTED]);

            Cancellation::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'original_amount' => $originalAmount,
                    'refund_amount' => $refundAmount,
                    'cancellation_fee' => $cancellationFee,
                    'reason' => $reasonText,
                    'status' => 'pending',
                    'cancellation_date' => now(),
                ]
            );

            $booking = $booking->fresh()->load('showtime.movie', 'showtime.theatre', 'user', 'cancellation');

            return [
                'success' => true,
                'message' => 'Cancellation request submitted. An admin will review it shortly.',
                'booking' => $booking,
                'status' => 200,
            ];
        });
    }

    /**
     * Admin approves a pending cancellation (finalizes refund + seat release).
     */
    public function approveCancellationRequest(Booking $booking): array
    {
        if ($booking->status !== BookingStatus::CANCELLATION_REQUESTED) {
            return [
                'success' => false,
                'message' => 'No pending cancellation for this booking.',
                'status' => 422,
            ];
        }

        $reason = $booking->cancellation?->reason ?? 'Approved by admin';

        return $this->finalizeCancellation($booking, $reason);
    }

    /**
     * Admin rejects a pending cancellation (booking returns to confirmed).
     */
    public function rejectCancellationRequest(Booking $booking): array
    {
        if ($booking->status !== BookingStatus::CANCELLATION_REQUESTED) {
            return [
                'success' => false,
                'message' => 'No pending cancellation for this booking.',
                'status' => 422,
            ];
        }

        $booking->update(['status' => BookingStatus::CONFIRMED]);

        if ($booking->cancellation) {
            $booking->cancellation->update(['status' => 'rejected']);
        }

        return [
            'success' => true,
            'message' => 'Cancellation request rejected. Booking remains confirmed.',
            'booking' => $booking->fresh()->load('showtime.movie', 'showtime.theatre', 'user'),
            'status' => 200,
        ];
    }

    /**
     * @deprecated Direct cancellation — use requestCancellation for customers.
     */
    public function cancelBooking(Booking $booking, string $reason, ?string $comments = null): array
    {
        return $this->requestCancellation($booking, $reason, $comments);
    }

    protected function finalizeCancellation(Booking $booking, string $reason): array
    {
        return DB::transaction(function () use ($booking, $reason) {
            $booking = Booking::lockForUpdate()->findOrFail($booking->id);

            if ($booking->status === BookingStatus::CANCELLED) {
                return [
                    'success' => false,
                    'message' => 'Booking already cancelled.',
                    'status' => 200,
                ];
            }

            $wasConfirmed = Payment::where('booking_id', $booking->id)
                ->where('payment_status', 'completed')
                ->exists()
                || $booking->status === BookingStatus::CONFIRMED;

            $originalAmount = $booking->total_price;
            $refundAmount = $originalAmount * 0.5;
            $cancellationFee = $originalAmount * 0.5;

            $booking->update(['status' => BookingStatus::CANCELLED]);

            $cancellation = Cancellation::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'original_amount' => $originalAmount,
                    'refund_amount' => $refundAmount,
                    'cancellation_fee' => $cancellationFee,
                    'reason' => $reason,
                    'status' => 'approved',
                    'cancellation_date' => now(),
                ]
            );

            if ($wasConfirmed) {
                $showtime = Showtime::lockForUpdate()->findOrFail($booking->showtime_id);
                $showtime->update([
                    'available_seats' => $showtime->available_seats + $booking->number_of_tickets,
                ]);
            }

            $booking = $booking->fresh()->load('showtime.movie', 'showtime.theatre', 'user');

            $this->sendBookingCancelledEmail($booking, $cancellation);

            return [
                'success' => true,
                'message' => 'Booking cancelled successfully',
                'refund_amount' => $refundAmount,
                'booking' => $booking,
                'status' => 200,
            ];
        });
    }

    protected function sendBookingConfirmedEmail(Booking $booking): void
    {
        $email = $booking->user?->email;
        if (! $email) {
            return;
        }

        try {
            Mail::to($email)->send(new BookingConfirmedMail($booking));
        } catch (\Throwable $e) {
            Log::error('Failed to send booking confirmation email.', [
                'booking_id' => $booking->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function sendBookingCancelledEmail(Booking $booking, Cancellation $cancellation): void
    {
        $email = $booking->user?->email;
        if (! $email) {
            return;
        }

        try {
            Mail::to($email)->send(new BookingCancelledMail($booking, $cancellation));
        } catch (\Throwable $e) {
            Log::error('Failed to send booking cancellation email.', [
                'booking_id' => $booking->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Persist safe payment metadata only (no CVV, no full card number for mock; no card data for Stripe).
     */
    protected function recordPayment(Booking $booking, array $paymentInput): void
    {
        if (($paymentInput['gateway'] ?? 'mock') === 'stripe') {
            Payment::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'amount' => $booking->total_price,
                    'payment_method' => 'stripe',
                    'payment_gateway' => 'stripe',
                    'card_last_four' => null,
                    'payment_status' => 'completed',
                    'transaction_id' => $paymentInput['transaction_id'] ?? $paymentInput['stripe_payment_intent_id'] ?? null,
                    'stripe_checkout_session_id' => $paymentInput['stripe_session_id'] ?? null,
                    'stripe_payment_intent_id' => $paymentInput['stripe_payment_intent_id'] ?? null,
                    'payment_date' => now(),
                ]
            );

            return;
        }

        $cardNumber = preg_replace('/\D/', '', $paymentInput['card_number'] ?? '');
        $cardLastFour = strlen($cardNumber) >= 4 ? substr($cardNumber, -4) : null;

        Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'amount' => $booking->total_price,
                'payment_method' => $paymentInput['payment_method'] ?? 'mock',
                'payment_gateway' => 'mock',
                'card_last_four' => $cardLastFour,
                'payment_status' => 'completed',
                'transaction_id' => 'MOCK-'.strtoupper(uniqid()),
                'payment_date' => now(),
            ]
        );
    }
}
