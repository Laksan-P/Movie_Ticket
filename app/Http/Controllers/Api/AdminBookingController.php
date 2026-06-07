<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Models\Booking;

class AdminBookingController extends Controller
{
    use RespondsWithJson;

    public function index()
    {
        $bookings = Booking::with(['user', 'showtime.movie', 'showtime.theatre', 'cancellation'])
            ->latest()
            ->get();

        return $this->jsonSuccess('Bookings retrieved successfully.', [
            'bookings' => $bookings,
        ]);
    }
}
