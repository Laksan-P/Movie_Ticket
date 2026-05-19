<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\StripePaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::redirect('/theatre', '/theatres');

Route::get('/', [MovieController::class, 'index'])->name('home');
Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/theatres', function(Illuminate\Http\Request $request) {
    $theatres = \App\Models\Theatre::where('is_active', true)->get();
    $selectedTheatreId = $request->query('theatre_id');
    $selectedTheatre = null;
    $uniqueMovies = [];

    if ($selectedTheatreId) {
        $selectedTheatre = \App\Models\Theatre::find($selectedTheatreId);
        $showtimes = \App\Models\Showtime::whereHas('movie', function($q) {
                $q->where('is_active', true);
            })
            ->with('movie')
            ->where('theatre_id', $selectedTheatreId)
            ->where('showtime', '>=', now())
            ->get();
        
        $uniqueMovies = $showtimes->groupBy('movie_id');
    }

    return view('theatres', compact('theatres', 'selectedTheatreId', 'selectedTheatre', 'uniqueMovies'));
})->name('theatres.index');

Route::get('/cancellation-policy', function() {
    return view('cancellation-policy');
})->name('cancellation.policy');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('bookings.index');
    })->name('dashboard');

    // Customer Routes
    Route::get('/bookings/create/{showtime}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    
    // Payment & Cancellations
    Route::get('/payment/{booking}', [BookingController::class, 'showPayment'])->name('bookings.payment');
    Route::post('/payment/{booking}/confirm', [BookingController::class, 'confirmPayment'])->name('bookings.confirm');
    Route::post('/payment/{booking}/stripe', [StripePaymentController::class, 'createCheckoutSession'])->name('bookings.payment.stripe');
    Route::get('/payment/{booking}/stripe/success', [StripePaymentController::class, 'success'])->name('bookings.payment.stripe.success');
    Route::get('/payment/{booking}/stripe/cancel', [StripePaymentController::class, 'cancel'])->name('bookings.payment.stripe.cancel');
    Route::get('/cancellation/{booking}', [BookingController::class, 'showCancellation'])->name('bookings.cancel');
    Route::post('/cancellation/{booking}/confirm', [BookingController::class, 'confirmCancellation'])->name('bookings.cancel.confirm');
});

// Admin Routes
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings.index');
    Route::get('/cancellations', [AdminController::class, 'cancellationsIndex'])->name('cancellations.index');
    
    // Movies
    Route::get('/movies', [AdminController::class, 'index'])->name('movies.index');
    Route::post('/movies', [AdminController::class, 'store'])->name('movies.store');
    Route::put('/movies/{movie}', [AdminController::class, 'update'])->name('movies.update');
    Route::delete('/movies/{movie}', [AdminController::class, 'destroy'])->name('movies.destroy');

    // Theatres
    Route::get('/theatres', [AdminController::class, 'theatresIndex'])->name('theatres.index');
    Route::post('/theatres', [AdminController::class, 'theatreStore'])->name('theatres.store');
    Route::put('/theatres/{theatre}', [AdminController::class, 'theatreUpdate'])->name('theatres.update');
    Route::delete('/theatres/{theatre}', [AdminController::class, 'theatreDestroy'])->name('theatres.destroy');

    // Showtimes
    Route::get('/showtimes', [AdminController::class, 'showtimesIndex'])->name('showtimes.index');
    Route::post('/showtimes', [AdminController::class, 'showtimeStore'])->name('showtimes.store');
    Route::delete('/showtimes/{showtime}', [AdminController::class, 'showtimeDestroy'])->name('showtimes.destroy');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');
