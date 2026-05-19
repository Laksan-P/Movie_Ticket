<x-movie-layout>
<section class="min-h-screen p-4 md:py-12 md:px-4 bg-[#F6F6F6] text-[#020617]">
    <div class="w-full mx-auto max-w-[80rem] px-4">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-[#020617] mb-2">My Bookings</h1>
            <p class="text-slate-400">View and manage your movie tickets</p>
        </div>

        <div class="flex flex-wrap gap-4 mb-8 border-b border-gray-200">
            <button onclick="switchTab('active')" id="active-tab" class="px-4 py-3 font-semibold border-b-2 border-transparent text-slate-500 hover:text-[#020617] transition-all bg-transparent cursor-pointer outline-none active-tab-btn">
                Active Bookings ({{ $activeBookings->count() }})
            </button>
            <button onclick="switchTab('past')" id="past-tab" class="px-4 py-3 font-semibold border-b-2 border-transparent text-slate-500 hover:text-[#020617] transition-all bg-transparent cursor-pointer outline-none">
                Past Bookings ({{ $pastBookings->count() }})
            </button>
            <button onclick="switchTab('cancelled')" id="cancelled-tab" class="px-4 py-3 font-semibold border-b-2 border-transparent text-slate-500 hover:text-[#020617] transition-all bg-transparent cursor-pointer outline-none">
                Cancelled ({{ $cancelledBookings->count() }})
            </button>
        </div>

        <div id="active-content" class="flex flex-col gap-6">
            @if($activeBookings->isEmpty())
                <div class="text-center py-20">
                    <h3 class="text-2xl font-bold text-[#020617] mb-2">No Active Bookings</h3>
                    <p class="text-slate-400 mb-6">You don't have any upcoming bookings yet.</p>
                    <a href="{{ route('theatres.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-[#020617] text-white font-bold no-underline transition-all">
                        Book Now →
                    </a>
                </div>
            @else
                @foreach ($activeBookings as $booking)
                    @include('partials.booking-card', ['booking' => $booking, 'variant' => 'active'])
                @endforeach
            @endif
        </div>

        <div id="past-content" class="hidden flex-col gap-6">
            @if($pastBookings->isEmpty())
                <div class="text-center py-20 text-slate-500">
                    <h3 class="text-2xl font-bold text-[#020617] mb-2">No Past Bookings</h3>
                    <p>Your completed or expired showtimes will appear here.</p>
                </div>
            @else
                @foreach ($pastBookings as $booking)
                    @include('partials.booking-card', ['booking' => $booking, 'variant' => 'past'])
                @endforeach
            @endif
        </div>

        <div id="cancelled-content" class="hidden flex-col gap-6">
            @if($cancelledBookings->isEmpty())
                <div class="text-center py-20 text-slate-500">
                    <h3 class="text-2xl font-bold text-[#020617] mb-2">No Cancellations</h3>
                    <p>You haven't cancelled any bookings yet.</p>
                </div>
            @else
                @foreach ($cancelledBookings as $booking)
                    <div class="rounded-lg border border-gray-200 bg-white p-6 opacity-75 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="md:col-span-2">
                                <h3 class="text-2xl font-bold text-[#020617] mb-3">{{ data_get($booking, 'showtime.movie.title') }}</h3>
                                <div class="space-y-2 text-sm text-slate-600">
                                    <p><strong class="text-slate-700">Theatre:</strong> {{ data_get($booking, 'showtime.theatre.name') }}</p>
                                    <p><strong class="text-slate-700">Date:</strong> {{ data_get($booking, 'showtime.showtime') ? \Carbon\Carbon::parse(data_get($booking, 'showtime.showtime'))->format('M d, Y') : 'N/A' }}</p>
                                    <p><strong class="text-slate-700">Tickets:</strong> {{ data_get($booking, 'number_of_tickets') }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col justify-center items-start md:items-center">
                                <p class="text-[10px] md:text-xs text-slate-500 uppercase mb-1">Refund Pending</p>
                                <p class="text-2xl font-bold text-red-600 mb-2">LKR {{ number_format(data_get($booking, 'total_price', 0) * 0.5, 2) }}</p>
                            </div>
                            <div class="flex flex-col justify-center gap-3">
                                <div class="flex items-center gap-2 md:justify-center">
                                    <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                    <span class="font-semibold text-red-600">Cancelled</span>
                                </div>
                                <p class="text-[10px] text-slate-500 md:text-center mt-2">Cancelled on:<br>{{ data_get($booking, 'updated_at') ? data_get($booking, 'updated_at')->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

<style>
    .active-tab-btn { border-bottom-color: #020617; color: #020617 !important; }
</style>

<script>
    function switchTab(tab) {
        document.getElementById('active-content').classList.toggle('hidden', tab !== 'active');
        document.getElementById('past-content').classList.toggle('hidden', tab !== 'past');
        document.getElementById('cancelled-content').classList.toggle('hidden', tab !== 'cancelled');

        document.getElementById('active-tab').classList.toggle('active-tab-btn', tab === 'active');
        document.getElementById('past-tab').classList.toggle('active-tab-btn', tab === 'past');
        document.getElementById('cancelled-tab').classList.toggle('active-tab-btn', tab === 'cancelled');
    }
</script>
</x-movie-layout>