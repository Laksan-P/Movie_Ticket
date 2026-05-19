<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\AuthorizesBookingOwnership;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    use AuthorizesBookingOwnership;

    public function __construct(
        protected BookingService $bookingService
    ) {}

    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->with('showtime.movie', 'showtime.theatre')
            ->latest()
            ->get();

        return response()->json($bookings);
    }

    public function store(Request $request)
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seats' => 'required|string',
            'number_of_tickets' => 'required|numeric|min:1',
        ]);

        try {
            $booking = $this->bookingService->createBooking(
                auth()->id(),
                (int) $request->showtime_id,
                $request->seats,
                (int) $request->number_of_tickets
            );
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }

        return response()->json([
            'message' => 'Booking initiated successfully',
            'booking' => $booking->load('showtime.movie', 'showtime.theatre'),
        ], 201);
    }

    public function confirmPayment(Request $request, Booking $booking)
    {
        if ($response = $this->authorizeBookingAccessJson($booking)) {
            return $response;
        }

        $request->validate([
            'payment_method' => 'nullable|in:debit_card,credit_card',
            'card_number' => 'nullable|string',
        ]);

        $result = $this->bookingService->confirmPayment($booking, $request->only([
            'payment_method',
            'card_number',
        ]));

        return response()->json([
            'message' => $result['message'],
            'booking' => $result['booking'] ?? $booking->load('showtime.movie', 'showtime.theatre'),
        ], $result['status']);
    }

    public function cancelBooking(Request $request, Booking $booking)
    {
        if ($response = $this->authorizeBookingAccessJson($booking)) {
            return $response;
        }

        $request->validate([
            'reason' => 'required|string',
            'comments' => 'nullable|string',
        ]);

        $result = $this->bookingService->cancelBooking(
            $booking,
            $request->reason,
            $request->comments
        );

        $payload = ['message' => $result['message']];
        if (isset($result['refund_amount'])) {
            $payload['refund_amount'] = $result['refund_amount'];
        }

        return response()->json($payload, $result['status']);
    }
}
