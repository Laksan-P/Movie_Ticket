<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'rating' => 'required|string|max:20',
            'duration' => 'required|numeric|min:1',
            'release_date' => 'required|date',
            'image' => 'required|string',
            'trailer_url' => 'nullable|string',
            'description' => 'nullable|string',
            'formats' => 'nullable|array',
            'languages' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $movie = Movie::create($data);

        return $this->jsonSuccess('Movie created successfully.', [
            'movie' => $movie,
        ], 201);
    }

    public function update(Request $request, Movie $movie)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'rating' => 'required|string|max:20',
            'duration' => 'required|numeric|min:1',
            'release_date' => 'required|date',
            'image' => 'required|string',
            'trailer_url' => 'nullable|string',
            'description' => 'nullable|string',
            'formats' => 'nullable|array',
            'languages' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $movie->update($data);

        return $this->jsonSuccess('Movie updated successfully.', [
            'movie' => $movie->fresh(),
        ]);
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();

        return $this->jsonSuccess('Movie deleted successfully.');
    }
}
