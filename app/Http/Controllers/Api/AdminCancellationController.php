<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;

class AdminCancellationController extends Controller
{
    use RespondsWithJson;

    public function __construct(
        protected BookingService $bookingService
    ) {}

    public function pending()
    {
        $bookings = Booking::with(['user', 'showtime.movie', 'showtime.theatre', 'cancellation'])
            ->where('status', 'cancellation_requested')
            ->latest()
            ->get();

        return $this->jsonSuccess('Pending cancellations retrieved successfully.', [
            'cancellations' => $bookings,
        ]);
    }

    public function approve(Booking $booking)
    {
        if ($booking->status !== 'cancellation_requested') {
            return $this->jsonError('No pending cancellation for this booking.', 422);
        }

        $result = $this->bookingService->approveCancellationRequest($booking);

        if (! $result['success']) {
            return $this->jsonError($result['message'], $result['status']);
        }

        $payload = [
            'booking' => $result['booking'] ?? $booking->fresh()->load('showtime.movie', 'showtime.theatre', 'user'),
        ];
        if (isset($result['refund_amount'])) {
            $payload['refund_amount'] = $result['refund_amount'];
        }

        return $this->jsonSuccess($result['message'], $payload, $result['status']);
    }

    public function reject(Booking $booking)
    {
        $result = $this->bookingService->rejectCancellationRequest($booking);

        if (! $result['success']) {
            return $this->jsonError($result['message'], $result['status']);
        }

        return $this->jsonSuccess($result['message'], [
            'booking' => $result['booking'],
        ]);
    }
}
