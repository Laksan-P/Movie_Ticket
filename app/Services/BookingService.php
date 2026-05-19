<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Cancellation;
use App\Models\Payment;
use App\Models\Showtime;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Seats held by pending or confirmed bookings for a showtime.
     */
    public function getUnavailableSeats(int $showtimeId): array
    {
        return Booking::where('showtime_id', $showtimeId)
            ->whereIn('status', ['pending', 'confirmed'])
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

            $this->recordMockPayment($booking, $paymentInput);

            return [
                'success' => true,
                'message' => 'Payment confirmed and booking finalized',
                'booking' => $booking->fresh()->load('showtime.movie', 'showtime.theatre'),
                'status' => 200,
            ];
        });
    }

    public function cancelBooking(Booking $booking, string $reason, ?string $comments = null): array
    {
        if ($booking->status === 'cancelled') {
            return [
                'success' => false,
                'message' => 'Booking already cancelled.',
                'status' => 200,
            ];
        }

        if (! in_array($booking->status, ['pending', 'confirmed'], true)) {
            return [
                'success' => false,
                'message' => 'This booking cannot be cancelled.',
                'status' => 422,
            ];
        }

        return DB::transaction(function () use ($booking, $reason, $comments) {
            $booking = Booking::lockForUpdate()->findOrFail($booking->id);

            if ($booking->status === 'cancelled') {
                return [
                    'success' => false,
                    'message' => 'Booking already cancelled.',
                    'status' => 200,
                ];
            }

            if (! in_array($booking->status, ['pending', 'confirmed'], true)) {
                return [
                    'success' => false,
                    'message' => 'This booking cannot be cancelled.',
                    'status' => 422,
                ];
            }

            $wasConfirmed = $booking->status === 'confirmed';
            $originalAmount = $booking->total_price;
            $refundAmount = $originalAmount * 0.5;
            $cancellationFee = $originalAmount * 0.5;

            $booking->update(['status' => 'cancelled']);

            Cancellation::create([
                'booking_id' => $booking->id,
                'original_amount' => $originalAmount,
                'refund_amount' => $refundAmount,
                'cancellation_fee' => $cancellationFee,
                'reason' => $reason.($comments ? ' - '.$comments : ''),
                'status' => 'approved',
                'cancellation_date' => now(),
            ]);

            if ($wasConfirmed) {
                $showtime = Showtime::lockForUpdate()->findOrFail($booking->showtime_id);
                $showtime->update([
                    'available_seats' => $showtime->available_seats + $booking->number_of_tickets,
                ]);
            }

            return [
                'success' => true,
                'message' => 'Booking cancelled successfully',
                'refund_amount' => $refundAmount,
                'status' => 200,
            ];
        });
    }

    /**
     * Mock payment: sensitive card data is never persisted (no CVV, no full PAN).
     */
    protected function recordMockPayment(Booking $booking, array $paymentInput): void
    {
        $cardNumber = preg_replace('/\D/', '', $paymentInput['card_number'] ?? '');
        $cardLastFour = strlen($cardNumber) >= 4 ? substr($cardNumber, -4) : null;

        Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'amount' => $booking->total_price,
                'payment_method' => $paymentInput['payment_method'] ?? 'mock',
                'card_last_four' => $cardLastFour,
                'payment_status' => 'completed',
                'transaction_id' => 'MOCK-'.strtoupper(uniqid()),
                'payment_date' => now(),
            ]
        );
    }
}
