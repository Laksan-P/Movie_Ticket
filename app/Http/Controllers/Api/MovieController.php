<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Models\Movie;

class MovieController extends Controller
{
    use RespondsWithJson;

    public function index()
    {
        $movies = Movie::where('is_active', true)->orderBy('title')->get();

        return $this->jsonSuccess('Movies retrieved successfully.', [
            'movies' => $movies,
        ]);
    }

    public function show(int $id)
    {
        $movie = Movie::with(['showtimes' => function ($query) {
            $query->with('theatre')->where('showtime', '>=', now())->orderBy('showtime');
        }])->find($id);

        if (! $movie) {
            return $this->jsonError('Movie not found.', 404);
        }

        return $this->jsonSuccess('Movie retrieved successfully.', [
            'movie' => $movie,
        ]);
    }
}
