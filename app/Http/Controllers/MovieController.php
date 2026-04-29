<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::where('is_active', true)->get();
        $sliderMovies = Movie::where('is_active', true)
            ->whereHas('showtimes', function ($query) {
                $query->where('showtime', '>=', now())
                    ->whereHas('theatre', function($q) {
                        $q->where('is_active', true);
                    });
            })
            ->latest()
            ->take(5)
            ->get();
        \Log::info('Movies count in index request: ' . $movies->count());
        return view('home', compact('movies', 'sliderMovies'));
    }

    public function show(Request $request, Movie $movie)
    {
        if (!$movie->is_active) {
            abort(404);
        }
        $theatreId = $request->query('theatre_id');
        
        $query = $movie->showtimes()
            ->with('theatre')
            ->whereHas('theatre', function($q) {
                $q->where('is_active', true);
            })
            ->where('showtime', '>=', now());
        
        if ($theatreId) {
            $query->where('theatre_id', $theatreId);
        }
        
        $showtimes = $query->get();
        
        $theatres = [];
        foreach ($showtimes as $st) {
            $theatres[$st->theatre_id]['details'] = [
                'name' => $st->theatre->name,
                'location' => $st->theatre->location
            ];
            $theatres[$st->theatre_id]['showtimes'][] = $st;
        }

        return view('movie', compact('movie', 'theatres'));
    }
}
