<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Models\Theatre;
use Illuminate\Http\Request;

class TheatreController extends Controller
{
    use RespondsWithJson;

    public function index()
    {
        $theatres = Theatre::where('is_active', true)->orderBy('name')->get();

        return $this->jsonSuccess('Theatres retrieved successfully.', [
            'theatres' => $theatres,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'total_seats' => 'required|numeric|min:1',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $theatre = Theatre::create($data);

        return $this->jsonSuccess('Theatre created successfully.', [
            'theatre' => $theatre,
        ], 201);
    }

    public function update(Request $request, Theatre $theatre)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'total_seats' => 'required|numeric|min:1',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $theatre->update($data);

        return $this->jsonSuccess('Theatre updated successfully.', [
            'theatre' => $theatre->fresh(),
        ]);
    }

    public function destroy(Theatre $theatre)
    {
        $theatre->delete();

        return $this->jsonSuccess('Theatre deleted successfully.');
    }
}
