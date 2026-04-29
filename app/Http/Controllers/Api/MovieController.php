<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        return response()->json(Movie::where('is_active', true)->get());
    }

    public function show($id)
    {
        $movie = Movie::with('showtimes.theatre')->find($id);
        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }
        return response()->json($movie);
    }
}
