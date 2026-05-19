<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Booking;
use Illuminate\Http\JsonResponse;

trait AuthorizesBookingOwnership
{
    protected function authorizeBookingAccess(Booking $booking): void
    {
        $user = auth()->user();

        if (! $user) {
            abort(403);
        }

        if ($user->isAdmin()) {
            return;
        }

        if ($booking->user_id !== $user->id) {
            abort(403);
        }
    }

    protected function authorizeBookingAccessJson(Booking $booking): ?JsonResponse
    {
        $user = auth()->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($user->isAdmin()) {
            return null;
        }

        if ($booking->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized access to this booking.'], 403);
        }

        return null;
    }
}
