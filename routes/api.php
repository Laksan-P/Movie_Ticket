<?php

use App\Http\Controllers\Api\AdminBookingController;
use App\Http\Controllers\Api\AdminCancellationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\ShowtimeController;
use App\Http\Controllers\Api\TheatreController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API routes (no Bearer token required)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/{movie}', [MovieController::class, 'show'])->whereNumber('movie');

Route::get('/theatres', [TheatreController::class, 'index']);
Route::get('/showtimes', [ShowtimeController::class, 'index']);
Route::get('/showtimes/{showtime}/booked-seats', [ShowtimeController::class, 'bookedSeats'])->whereNumber('showtime');

/*
|--------------------------------------------------------------------------
| Protected API routes (Authorization: Bearer {token})
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'show']);

    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirmPayment']);
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancelBooking']);

    Route::middleware('admin')->group(function () {
        Route::post('/movies', [MovieController::class, 'store']);
        Route::put('/movies/{movie}', [MovieController::class, 'update'])->whereNumber('movie');
        Route::delete('/movies/{movie}', [MovieController::class, 'destroy'])->whereNumber('movie');

        Route::post('/theatres', [TheatreController::class, 'store']);
        Route::put('/theatres/{theatre}', [TheatreController::class, 'update'])->whereNumber('theatre');
        Route::delete('/theatres/{theatre}', [TheatreController::class, 'destroy'])->whereNumber('theatre');

        Route::post('/showtimes', [ShowtimeController::class, 'store']);
        Route::put('/showtimes/{showtime}', [ShowtimeController::class, 'update'])->whereNumber('showtime');
        Route::delete('/showtimes/{showtime}', [ShowtimeController::class, 'destroy'])->whereNumber('showtime');

        Route::get('/admin/bookings', [AdminBookingController::class, 'index']);
        Route::get('/admin/cancellations/pending', [AdminCancellationController::class, 'pending']);
        Route::post('/admin/cancellations/{booking}/approve', [AdminCancellationController::class, 'approve'])->whereNumber('booking');
        Route::post('/admin/cancellations/{booking}/reject', [AdminCancellationController::class, 'reject'])->whereNumber('booking');
    });
});
