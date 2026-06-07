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
        $userId = auth()->id();

        // Prevent SQL Injection using Laravel Eloquent ORM (scoped user bookings)
        $activeBookings = Booking::query()
            ->forUser($userId)
            ->withShowtimeDetails()
            ->active()
            ->latest()
            ->get();

        $pastBookings = Booking::query()
            ->forUser($userId)
            ->withShowtimeDetails()
            ->past()
            ->latest()
            ->get();

        $cancelledBookings = Booking::query()
            ->forUser($userId)
            ->withShowtimeDetails()
            ->cancelled()
            ->latest()
            ->get();

        return view('my-bookings', compact('activeBookings', 'pastBookings', 'cancelledBookings'));
    }

    public function create(Showtime $showtime)
    {
        $showtime->load(['movie', 'theatre']);

        return view('booking', compact('showtime'));
    }

    public function store(Request $request)
    {
        // Validate incoming request data before processing
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
        // Prevent unauthorized booking access
        $this->authorizeBookingAccess($booking);
        $booking->load(['showtime.movie', 'showtime.theatre']);

        return view('payment', compact('booking'));
    }

    public function confirmPayment(Request $request, Booking $booking)
    {
        // Prevent unauthorized booking access
        $this->authorizeBookingAccess($booking);

        // Validate incoming request data before processing (payment security — no CVV stored)
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
        // Prevent unauthorized booking access
        $this->authorizeBookingAccess($booking);
        $booking->load(['showtime.movie', 'showtime.theatre']);

        if ($blockReason = $this->bookingService->cancellationBlockReason($booking)) {
            return redirect()->route('bookings.index')->with('error', $blockReason);
        }

        return view('cancellation', compact('booking'));
    }

    public function confirmCancellation(Request $request, Booking $booking)
    {
        // Prevent unauthorized booking access
        $this->authorizeBookingAccess($booking);

        // Validate incoming request data before processing
        $request->validate([
            'reason' => 'required|string',
            'comments' => 'nullable|string',
        ]);

        // Prevent XSS attacks by sanitizing user input before storage
        $cleanReason = strip_tags($request->reason);
        $cleanComments = $request->comments ? strip_tags($request->comments) : null;

        $result = $this->bookingService->requestCancellation(
            $booking,
            $cleanReason,
            $cleanComments
        );

        if (! $result['success']) {
            return redirect()->route('bookings.cancel', $booking->id)
                ->with('error', $result['message']);
        }

        return redirect()->route('bookings.index')->with('success', $result['message']);
    }
}
