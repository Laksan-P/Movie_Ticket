<x-movie-layout>
<section class="min-h-screen p-4 md:py-12 md:px-4 bg-[#F6F6F6] text-[#020617]">
    <div class="w-full mx-auto max-w-[80rem] px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-[#020617] mb-2">My Bookings</h1>
            <p class="text-slate-400">View and manage your movie tickets</p>
        </div>

        <!-- Tabs -->
        <div class="flex gap-4 mb-8 border-b border-gray-200">
            <button onclick="switchTab('active')" id="active-tab" class="px-4 py-3 font-semibold border-b-2 border-transparent text-slate-500 hover:text-[#020617] transition-all bg-transparent cursor-pointer outline-none active-tab-btn">
                Active Bookings ({{ collect($bookings)->where('status', 'confirmed')->count() }})
            </button>
            <button onclick="switchTab('cancelled')" id="cancelled-tab" class="px-4 py-3 font-semibold border-b-2 border-transparent text-slate-500 hover:text-[#020617] transition-all bg-transparent cursor-pointer outline-none">
                Cancelled ({{ collect($bookings)->where('status', 'cancelled')->count() }})
            </button>
        </div>

        <!-- Active Bookings Tab -->
        <div id="active-content" class="flex flex-col gap-6">
            @php $activeBookings = collect($bookings)->where('status', 'confirmed'); @endphp
            @if($activeBookings->isEmpty())
                <div class="text-center py-20">
                    <h3 class="text-2xl font-bold text-[#020617] mb-2">No Active Bookings</h3>
                    <p class="text-slate-400 mb-6">You don't have any active bookings yet.</p>
                    <a href="{{ route('theatres.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-[#020617] text-white font-bold no-underline transition-all">
                        Book Now →
                    </a>
                </div>
            @else
                @foreach ($activeBookings as $booking)
                    <div class="rounded-lg border border-gray-200 bg-white p-6 transition-all hover:border-[#020617]/50 group shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- Movie Info -->
                            <div class="md:col-span-2">
                                <h3 class="text-2xl font-bold text-[#020617] mb-3">{{ data_get($booking, 'showtime.movie.title') }}</h3>
                                <div class="space-y-2 text-sm text-slate-600">
                                    <p><strong class="text-slate-700">Theatre:</strong> {{ data_get($booking, 'showtime.theatre.name') }}</p>
                                    <p><strong class="text-slate-700">Date:</strong> {{ data_get($booking, 'showtime.showtime') ? \Carbon\Carbon::parse(data_get($booking, 'showtime.showtime'))->format('M d, Y') : 'N/A' }}</p>
                                    <p><strong class="text-slate-700">Time:</strong> {{ data_get($booking, 'showtime.showtime') ? \Carbon\Carbon::parse(data_get($booking, 'showtime.showtime'))->format('h:i A') : 'N/A' }}</p>
                                    <p><strong class="text-slate-700">Price per Ticket:</strong> LKR {{ number_format(data_get($booking, 'showtime.ticket_price', 0), 2) }}</p>
                                    <p><strong class="text-slate-700">Tickets:</strong> {{ data_get($booking, 'number_of_tickets') }}</p>
                                    <p><strong class="text-slate-700">Booking Date:</strong> {{ data_get($booking, 'created_at') ? data_get($booking, 'created_at')->format('M d, Y') : 'N/A' }}</p>
                                </div>
                            </div>

                            <!-- Price & Status -->
                            <div class="flex flex-col justify-center items-start md:items-center">
                                <p class="text-[10px] md:text-xs text-slate-500 uppercase mb-1">Total Amount</p>
                                <p class="text-2xl md:text-3xl font-bold text-[#020617] mb-4">LKR {{ number_format(data_get($booking, 'total_price', 0), 2) }}</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></div>
                                    <span class="font-semibold text-green-700">Confirmed</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col gap-3 justify-center">
                                <button onclick="alert('Ticket Details:\n\nMovie: {{ data_get($booking, 'showtime.movie.title') }}\nTheatre: {{ data_get($booking, 'showtime.theatre.name') }}\nDate: {{ data_get($booking, 'showtime.showtime') ? \Carbon\Carbon::parse(data_get($booking, 'showtime.showtime'))->format('M d, Y') : 'N/A' }}\nTime: {{ data_get($booking, 'showtime.showtime') ? \Carbon\Carbon::parse(data_get($booking, 'showtime.showtime'))->format('h:i A') : 'N/A' }}\nSeats: {{ data_get($booking, 'seats') }}\nTotal: LKR {{ number_format(data_get($booking, 'total_price', 0), 2) }}')" class="w-full py-2.5 px-4 rounded-lg border-2 border-[#020617] text-[#020617] font-bold bg-transparent cursor-pointer transition-all hover:bg-[#020617]/10">
                                    View Details
                                </button>
                                <a href="{{ route('bookings.cancel', $booking->id) }}" class="w-full py-2.5 px-4 rounded-lg bg-[#020617] text-white font-bold text-center no-underline transition-all">
                                    Cancel Booking
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Cancelled Bookings Tab -->
        <div id="cancelled-content" class="hidden flex flex-col gap-6">
            @php $cancelledBookings = collect($bookings)->where('status', 'cancelled'); @endphp
            @if($cancelledBookings->isEmpty())
                <div class="text-center py-20 text-slate-500">
                    <h3 class="text-2xl font-bold text-[#020617] mb-2">No Cancellations</h3>
                    <p>You haven't cancelled any bookings yet.</p>
                </div>
            @else
                @foreach ($cancelledBookings as $booking)
                    <div class="rounded-lg border border-gray-200 bg-white p-6 opacity-75 shadow-sm mb-6">
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
        document.getElementById('cancelled-content').classList.toggle('hidden', tab !== 'cancelled');
        
        document.getElementById('active-tab').classList.toggle('active-tab-btn', tab === 'active');
        document.getElementById('cancelled-tab').classList.toggle('active-tab-btn', tab === 'cancelled');
    }
</script>
</x-movie-layout>
