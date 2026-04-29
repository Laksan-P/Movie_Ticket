<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use App\Models\Booking;
use App\Models\Cancellation;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()->bookings()->with('showtime.movie', 'showtime.theatre')->latest()->get();
        return view('my-bookings', compact('bookings'));
    }

    public function create(Showtime $showtime)
    {
        $showtime->load(['movie', 'theatre']);
        // Get booked seats
        $bookedSeats = Booking::where('showtime_id', $showtime->id)
            ->where('status', 'confirmed')
            ->pluck('seats')
            ->flatMap(function($seats) {
                return explode(',', $seats);
            })->toArray();

        return view('booking', compact('showtime', 'bookedSeats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seats' => 'required|string',
            'number_of_tickets' => 'required|numeric|min:1',
        ]);

        $showtime = Showtime::findOrFail($request->showtime_id);
        $total_price = $showtime->ticket_price * $request->number_of_tickets;

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'showtime_id' => $request->showtime_id,
            'number_of_tickets' => $request->number_of_tickets,
            'seats' => $request->seats,
            'total_price' => $total_price,
            'status' => 'pending', // Set to pending until payment
        ]);

        return redirect()->route('bookings.payment', $booking->id);
    }

    public function showPayment(Booking $booking)
    {
        $booking->load(['showtime.movie', 'showtime.theatre']);
        return view('payment', compact('booking'));
    }

    public function confirmPayment(Request $request, Booking $booking)
    {
        // Simple mock payment
        $booking->update(['status' => 'confirmed']);
        
        // Decrease available seats
        $booking->showtime->decrement('available_seats', $booking->number_of_tickets);

        return redirect()->route('bookings.index')->with('success', 'Booking confirmed!');
    }

    public function showCancellation(Booking $booking)
    {
        $booking->load(['showtime.movie', 'showtime.theatre']);
        return view('cancellation', compact('booking'));
    }

    public function confirmCancellation(Request $request, Booking $booking)
    {
        $request->validate([
            'reason' => 'required|string',
            'comments' => 'nullable|string',
        ]);

        $booking->update(['status' => 'cancelled']);
        
        // Create cancellation record
        $originalAmount = $booking->total_price;
        $refundAmount = $originalAmount * 0.5;
        $cancellationFee = $originalAmount * 0.5;

        Cancellation::create([
            'booking_id' => $booking->id,
            'original_amount' => $originalAmount,
            'refund_amount' => $refundAmount,
            'cancellation_fee' => $cancellationFee,
            'reason' => $request->reason . ($request->comments ? ' - ' . $request->comments : ''),
            'status' => 'approved',
            'cancellation_date' => now(),
        ]);
        
        // Return seats
        $booking->showtime->increment('available_seats', $booking->number_of_tickets);

        return redirect()->route('bookings.index')->with('success', 'Booking cancelled successfully.');
    }
}
