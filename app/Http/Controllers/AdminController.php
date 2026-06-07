<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Cancellation;
use App\Models\Movie;
use App\Models\Theatre;
use App\Services\BookingService;
use App\Support\BookingStatus;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    public function dashboard()
    {
        $totalBookings = Booking::count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $confirmedRevenue = Booking::where('status', 'confirmed')->sum('total_price');
        $cancellationFees = Cancellation::sum('cancellation_fee');
        $totalRevenue = $confirmedRevenue + $cancellationFees;
        $pendingCancellations = Booking::where('status', BookingStatus::CANCELLATION_REQUESTED)->count();
        $totalCancellations = Booking::where('status', BookingStatus::CANCELLED)->count();
        $totalTheatres = Theatre::count();
        $totalMovies = Movie::count();

        $recentBookings = Booking::with(['user', 'showtime.movie', 'showtime.theatre'])
            ->latest()
            ->take(5)
            ->get();

        $theatres = Theatre::latest()->take(3)->get();
        $movies = Movie::latest()->take(3)->get();

        return view('admin-dashboard', compact(
            'totalBookings', 
            'confirmedBookings', 
            'totalRevenue', 
            'pendingCancellations',
            'totalCancellations', 
            'totalTheatres', 
            'totalMovies', 
            'recentBookings', 
            'theatres', 
            'movies'
        ));
    }

    public function bookings()
    {
        $bookings = Booking::with(['user', 'showtime.movie', 'showtime.theatre'])->latest()->get();
        $title = 'Booking Management';
        return view('admin-bookings', compact('bookings', 'title'));
    }

    // Movie Management
    public function index()
    {
        $movies = Movie::latest()->get();
        return view('admin-movies', compact('movies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'genre' => 'required',
            'rating' => 'required',
            'duration' => 'required|numeric',
            'release_date' => 'required|date',
            'image' => 'required',
            'trailer_url' => 'nullable',
            'description' => 'nullable',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
 
        Movie::create($data);

        return redirect()->route('admin.movies.index')->with('success', 'Movie added to registry.');
    }

    public function update(Request $request, Movie $movie)
    {
        $data = $request->validate([
            'title' => 'required',
            'genre' => 'required',
            'rating' => 'required',
            'duration' => 'required|numeric',
            'release_date' => 'required|date',
            'image' => 'required',
            'trailer_url' => 'nullable',
            'description' => 'nullable',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        
        \Log::info('MOVIE UPDATE DEBUG', [
            'movie_id' => $movie->id,
            'request_is_active_raw' => $request->input('is_active'),
            'request_boolean' => $request->boolean('is_active'),
            'data_is_active' => $data['is_active'],
            'all_input' => $request->all(),
        ]);
        
        $movie->update($data);

        return redirect()->route('admin.movies.index')->with('success', 'Registry entry updated.');
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();
        return redirect()->route('admin.movies.index')->with('success', 'Record removed from system.');
    }

    // Theatre Management
    public function theatresIndex()
    {
        $theatres = Theatre::latest()->get();
        return view('admin-theatres', compact('theatres'));
    }

    public function theatreStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'location' => 'required',
            'total_seats' => 'required|numeric',
            'description' => 'nullable',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        Theatre::create($data);
        return redirect()->route('admin.theatres.index')->with('success', 'Theatre registered.');
    }

    public function theatreUpdate(Request $request, Theatre $theatre)
    {
        $data = $request->validate([
            'name' => 'required',
            'location' => 'required',
            'total_seats' => 'required|numeric',
            'description' => 'nullable',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $theatre->update($data);
        return redirect()->route('admin.theatres.index')->with('success', 'Theatre updated.');
    }

    public function theatreDestroy(Theatre $theatre)
    {
        $theatre->delete();
        return redirect()->route('admin.theatres.index')->with('success', 'Theatre purged.');
    }

    // Showtime Management
    public function showtimesIndex()
    {
        $showtimes = \App\Models\Showtime::with(['movie', 'theatre'])->latest()->get();
        $movies = Movie::orderBy('title')->get();
        $theatres = Theatre::orderBy('name')->get();
        return view('admin-showtimes', compact('showtimes', 'movies', 'theatres'));
    }

    public function showtimeStore(Request $request)
    {
        $data = $request->validate([
            'theatre_id' => 'required|exists:theatres,id',
            'movie_id' => 'required|exists:movies,id',
            'showtime' => 'required',
            'ticket_price' => 'required|numeric',
            'format' => 'required',
            'language' => 'required',
        ]);

        $theatre = Theatre::find($request->theatre_id);
        $data['available_seats'] = $theatre->total_seats;

        \App\Models\Showtime::create($data);
        return redirect()->route('admin.showtimes.index')->with('success', 'Showtime session deployed.');
    }

    public function showtimeDestroy(\App\Models\Showtime $showtime)
    {
        $showtime->delete();
        return redirect()->route('admin.showtimes.index')->with('success', 'Showtime slot purged.');
    }

    public function cancellationsIndex()
    {
        $pendingCancellations = Booking::with(['user', 'showtime.movie', 'showtime.theatre', 'cancellation'])
            ->cancellationRequested()
            ->latest()
            ->get();

        $cancelledBookings = Booking::with(['user', 'showtime.movie', 'showtime.theatre', 'cancellation'])
            ->cancelled()
            ->latest()
            ->get();

        return view('admin-cancellations', compact('pendingCancellations', 'cancelledBookings'));
    }

    public function approveCancellation(Booking $booking)
    {
        $result = $this->bookingService->approveCancellationRequest($booking);

        if (! $result['success']) {
            return redirect()->route('admin.cancellations.index')
                ->with('error', $result['message']);
        }

        return redirect()->route('admin.cancellations.index')
            ->with('success', $result['message']);
    }

    public function rejectCancellation(Booking $booking)
    {
        $result = $this->bookingService->rejectCancellationRequest($booking);

        if (! $result['success']) {
            return redirect()->route('admin.cancellations.index')
                ->with('error', $result['message']);
        }

        return redirect()->route('admin.cancellations.index')
            ->with('success', $result['message']);
    }
}
