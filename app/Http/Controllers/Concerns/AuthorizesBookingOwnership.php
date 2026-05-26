<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Booking;
use Illuminate\Http\JsonResponse;

trait AuthorizesBookingOwnership
{
    /**
     * Prevent unauthorized booking access — users may only act on their own bookings (admins exempt).
     */
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

    /**
     * Prevent unauthorized booking access for API requests (returns JSON 401/403 instead of abort).
     */
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
