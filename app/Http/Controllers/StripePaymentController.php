<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesBookingOwnership;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripePaymentController extends Controller
{
    use AuthorizesBookingOwnership;

    public function __construct(
        protected BookingService $bookingService
    ) {}

    protected function stripeConfigured(): bool
    {
        // Secure environment variable usage — Stripe keys loaded from config/services.php (.env)
        return filled(config('services.stripe.secret')) && filled(config('services.stripe.key'));
    }

    public function createCheckoutSession(Request $request, Booking $booking)
    {
        // Prevent unauthorized booking access
        $this->authorizeBookingAccess($booking);

        if (! $this->stripeConfigured()) {
            return redirect()->route('bookings.payment', $booking)
                ->with('error', 'Stripe is not configured. Add STRIPE_KEY and STRIPE_SECRET to your .env file.');
        }

        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.index')
                ->with('error', 'This booking can no longer be paid.');
        }

        // Validate incoming request data before processing
        $request->validate([
            'accept_terms' => 'accepted',
        ]);

        // Secure environment variable usage — Stripe secret key never hard-coded
        Stripe::setApiKey(config('services.stripe.secret'));

        $booking->load(['showtime.movie', 'user']);

        $amount = (int) round((float) $booking->total_price * 100);
        if ($amount < 1) {
            return redirect()->route('bookings.payment', $booking)
                ->with('error', 'Invalid payment amount.');
        }

        $movieTitle = $booking->showtime?->movie?->title ?? 'Movie Ticket';
        $customerEmail = $booking->user?->email ?? auth()->user()->email;

        try {
            // Payment security — card data handled by Stripe Checkout; app stores metadata only
            $session = Session::create([
                'mode' => 'payment',
                'customer_email' => $customerEmail,
                'line_items' => [[
                    'quantity' => 1,
                    'price_data' => [
                        'currency' => strtolower(config('services.stripe.currency', 'lkr')),
                        'unit_amount' => $amount,
                        'product_data' => [
                            'name' => $movieTitle,
                            'description' => 'Booking #'.$booking->id,
                        ],
                    ],
                ]],
                'metadata' => [
                    'booking_id' => (string) $booking->id,
                    'user_id' => (string) auth()->id(),
                    'movie_title' => $movieTitle,
                ],
                'success_url' => route('bookings.payment.stripe.success', $booking).'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('bookings.payment.stripe.cancel', $booking),
            ]);
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('bookings.payment', $booking)
                ->with('error', 'Could not start Stripe checkout. Please try again or use demo payment.');
        }

        Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'amount' => $booking->total_price,
                'payment_method' => 'stripe',
                'payment_gateway' => 'stripe',
                'payment_status' => 'pending',
                'stripe_checkout_session_id' => $session->id,
                'payment_date' => now(),
            ]
        );

        return redirect()->away($session->url);
    }

    public function success(Request $request, Booking $booking)
    {
        // Prevent unauthorized booking access
        $this->authorizeBookingAccess($booking);

        if (! $this->stripeConfigured()) {
            return redirect()->route('bookings.payment', $booking)
                ->with('error', 'Stripe is not configured.');
        }

        $sessionId = $request->query('session_id');
        if (! $sessionId) {
            return redirect()->route('bookings.payment', $booking)
                ->with('error', 'Missing Stripe session.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = Session::retrieve($sessionId);
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('bookings.payment', $booking)
                ->with('error', 'Could not verify Stripe payment.');
        }

        // Payment security — verify session belongs to this booking (prevent callback tampering)
        if ((string) ($session->metadata['booking_id'] ?? '') !== (string) $booking->id) {
            abort(403);
        }

        if ($session->payment_status !== 'paid') {
            return redirect()->route('bookings.payment', $booking)
                ->with('error', 'Payment was not completed.');
        }

        $paymentIntentId = is_string($session->payment_intent)
            ? $session->payment_intent
            : ($session->payment_intent->id ?? null);

        // Idempotent confirmation via BookingService (no double confirm / double seat reduction)
        $result = $this->bookingService->confirmPayment($booking, [
            'gateway' => 'stripe',
            'stripe_session_id' => $session->id,
            'stripe_payment_intent_id' => $paymentIntentId,
            'transaction_id' => $paymentIntentId ?? $session->id,
        ]);

        if (! $result['success']) {
            if ($booking->fresh()->status === 'confirmed') {
                return redirect()->route('bookings.index')
                    ->with('success', 'Payment already confirmed.');
            }

            return redirect()->route('bookings.payment', $booking)
                ->with('error', $result['message']);
        }

        return redirect()->route('bookings.index')
            ->with('success', $result['message']);
    }

    public function cancel(Booking $booking)
    {
        // Prevent unauthorized booking access
        $this->authorizeBookingAccess($booking);

        return redirect()->route('bookings.payment', $booking)
            ->with('info', 'Stripe checkout was cancelled. Your booking is still pending — you can try again or use demo payment.');
    }
}
