<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\RespondsWithJson;
use App\Http\Controllers\Concerns\AuthorizesBookingOwnership;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    use AuthorizesBookingOwnership;
    use RespondsWithJson;

    public function __construct(
        protected BookingService $bookingService
    ) {}

    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->with('showtime.movie', 'showtime.theatre')
            ->latest()
            ->get();

        return $this->jsonSuccess('Bookings retrieved successfully.', [
            'bookings' => $bookings,
        ]);
    }

    public function store(Request $request)
    {
        // Validate incoming request data before processing
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seats' => 'required|string',
            'number_of_tickets' => 'required|integer|min:1',
        ]);

        try {
            $booking = $this->bookingService->createBooking(
                auth()->id(),
                (int) $request->showtime_id,
                $request->seats,
                (int) $request->number_of_tickets
            );
        } catch (ValidationException $e) {
            return $this->jsonError('Validation failed.', 422, $e->errors());
        }

        $booking->load('showtime.movie', 'showtime.theatre');

        return $this->jsonSuccess('Booking initiated successfully.', [
            'booking' => $booking,
        ], 201);
    }

    public function confirmPayment(Request $request, Booking $booking)
    {
        // Prevent unauthorized booking access
        if ($response = $this->authorizeBookingAccessJson($booking)) {
            return $response;
        }

        // Validate incoming request data before processing (payment security — no CVV stored)
        $request->validate([
            'payment_method' => 'nullable|in:debit_card,credit_card',
            'card_number' => 'nullable|string',
        ]);

        $result = $this->bookingService->confirmPayment($booking, $request->only([
            'payment_method',
            'card_number',
        ]));

        $payload = [
            'booking' => $result['booking'] ?? $booking->load('showtime.movie', 'showtime.theatre'),
        ];

        if ($result['success']) {
            return $this->jsonSuccess($result['message'], $payload, $result['status']);
        }

        return $this->jsonError($result['message'], $result['status'], null);
    }

    public function cancelBooking(Request $request, Booking $booking)
    {
        // Prevent unauthorized booking access
        if ($response = $this->authorizeBookingAccessJson($booking)) {
            return $response;
        }

        // Validate incoming request data before processing
        $request->validate([
            'reason' => 'required|string|max:255',
            'comments' => 'nullable|string|max:1000',
        ]);

        // Prevent XSS attacks by sanitizing user input before storage
        $cleanReason = strip_tags($request->reason);
        $cleanComments = $request->comments ? strip_tags($request->comments) : null;

        $result = $this->bookingService->cancelBooking(
            $booking,
            $cleanReason,
            $cleanComments
        );

        $data = [];
        if (isset($result['refund_amount'])) {
            $data['refund_amount'] = $result['refund_amount'];
        }
        if (isset($result['booking'])) {
            $data['booking'] = $result['booking'];
        }

        if ($result['success']) {
            return $this->jsonSuccess($result['message'], $data ?: null, $result['status']);
        }

        return $this->jsonError($result['message'], $result['status'], null);
    }
}
