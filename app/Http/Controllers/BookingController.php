<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesBookingOwnership;
use App\Models\Booking;
use App\Models\Showtime;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    use AuthorizesBookingOwnership;

    public function __construct(
        protected BookingService $bookingService
    ) {}

    public function index()
    {
        $bookings = auth()->user()->bookings()->with('showtime.movie', 'showtime.theatre')->latest()->get();

        return view('my-bookings', compact('bookings'));
    }

    public function create(Showtime $showtime)
    {
        $showtime->load(['movie', 'theatre']);

        return view('booking', compact('showtime'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seats' => 'required|string',
            'number_of_tickets' => 'required|numeric|min:1',
        ]);

        $booking = $this->bookingService->createBooking(
            auth()->id(),
            (int) $request->showtime_id,
            $request->seats,
            (int) $request->number_of_tickets
        );

        return redirect()->route('bookings.payment', $booking->id);
    }

    public function showPayment(Booking $booking)
    {
        $this->authorizeBookingAccess($booking);
        $booking->load(['showtime.movie', 'showtime.theatre']);

        return view('payment', compact('booking'));
    }

    public function confirmPayment(Request $request, Booking $booking)
    {
        $this->authorizeBookingAccess($booking);

        $request->validate([
            'payment_method' => 'nullable|in:debit_card,credit_card',
            'card_number' => 'nullable|string',
        ]);

        $result = $this->bookingService->confirmPayment($booking, $request->only([
            'payment_method',
            'card_number',
        ]));

        if (! $result['success']) {
            return redirect()->route('bookings.payment', $booking->id)
                ->with('error', $result['message']);
        }

        return redirect()->route('bookings.index')->with('success', $result['message']);
    }

    public function showCancellation(Booking $booking)
    {
        $this->authorizeBookingAccess($booking);
        $booking->load(['showtime.movie', 'showtime.theatre']);

        return view('cancellation', compact('booking'));
    }

    public function confirmCancellation(Request $request, Booking $booking)
    {
        $this->authorizeBookingAccess($booking);

        $request->validate([
            'reason' => 'required|string',
            'comments' => 'nullable|string',
        ]);

        $result = $this->bookingService->cancelBooking(
            $booking,
            $request->reason,
            $request->comments
        );

        if (! $result['success']) {
            return redirect()->route('bookings.cancel', $booking->id)
                ->with('error', $result['message']);
        }

        return redirect()->route('bookings.index')->with('success', $result['message']);
    }
}
