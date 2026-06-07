<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Showtime;
use App\Models\Theatre;
use Illuminate\Http\Request;

class ShowtimeController extends Controller
{
    use RespondsWithJson;

    public function index(Request $request)
    {
        $query = Showtime::with(['movie', 'theatre'])
            ->where('showtime', '>=', now())
            ->orderBy('showtime');

        if ($request->filled('movie_id')) {
            $query->where('movie_id', (int) $request->movie_id);
        }

        if ($request->filled('theatre_id')) {
            $query->where('theatre_id', (int) $request->theatre_id);
        }

        return $this->jsonSuccess('Showtimes retrieved successfully.', [
            'showtimes' => $query->get(),
        ]);
    }

    public function bookedSeats(Showtime $showtime)
    {
        $seats = Booking::where('showtime_id', $showtime->id)
            ->whereIn('status', ['pending', 'confirmed', 'cancellation_requested'])
            ->pluck('seats')
            ->flatMap(fn (string $csv) => array_filter(array_map('trim', explode(',', $csv))))
            ->unique()
            ->values()
            ->all();

        return $this->jsonSuccess('Booked seats retrieved successfully.', [
            'seats' => $seats,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'theatre_id' => 'required|exists:theatres,id',
            'movie_id' => 'required|exists:movies,id',
            'showtime' => 'required|date',
            'ticket_price' => 'required|numeric|min:0',
            'format' => 'required|string|max:50',
            'language' => 'required|string|max:50',
            'available_seats' => 'nullable|integer|min:0',
        ]);

        if (! isset($data['available_seats'])) {
            $theatre = Theatre::findOrFail($data['theatre_id']);
            $data['available_seats'] = $theatre->total_seats;
        }

        $showtime = Showtime::create($data)->load(['movie', 'theatre']);

        return $this->jsonSuccess('Showtime created successfully.', [
            'showtime' => $showtime,
        ], 201);
    }

    public function update(Request $request, Showtime $showtime)
    {
        $data = $request->validate([
            'theatre_id' => 'sometimes|exists:theatres,id',
            'movie_id' => 'sometimes|exists:movies,id',
            'showtime' => 'sometimes|date',
            'ticket_price' => 'sometimes|numeric|min:0',
            'format' => 'sometimes|string|max:50',
            'language' => 'sometimes|string|max:50',
            'available_seats' => 'sometimes|integer|min:0',
        ]);

        $showtime->update($data);

        return $this->jsonSuccess('Showtime updated successfully.', [
            'showtime' => $showtime->fresh()->load(['movie', 'theatre']),
        ]);
    }

    public function destroy(Showtime $showtime)
    {
        $showtime->delete();

        return $this->jsonSuccess('Showtime deleted successfully.');
    }
}
