@php
    $isPast = ($variant ?? 'active') === 'past';
    $statusLabel = $isPast
        ? ($booking->status === 'confirmed' ? 'Completed' : 'Past')
        : ($booking->status === 'pending' ? 'Pending Payment' : 'Confirmed');
    $statusColor = $isPast
        ? 'text-slate-600'
        : ($booking->status === 'pending' ? 'text-amber-700' : 'text-green-700');
    $dotColor = $isPast
        ? 'bg-slate-400'
        : ($booking->status === 'pending' ? 'bg-amber-500' : 'bg-green-500');
    $showtimeFormatted = data_get($booking, 'showtime.showtime')
        ? \Carbon\Carbon::parse(data_get($booking, 'showtime.showtime'))
        : null;
@endphp

<div class="rounded-lg border border-gray-200 bg-white p-6 transition-all hover:border-[#020617]/50 group shadow-sm {{ $isPast ? 'opacity-90' : '' }}">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="md:col-span-2">
            <h3 class="text-2xl font-bold text-[#020617] mb-3">{{ data_get($booking, 'showtime.movie.title') }}</h3>
            <div class="space-y-2 text-sm text-slate-600">
                <p><strong class="text-slate-700">Theatre:</strong> {{ data_get($booking, 'showtime.theatre.name') }}</p>
                <p><strong class="text-slate-700">Date:</strong> {{ $showtimeFormatted ? $showtimeFormatted->format('M d, Y') : 'N/A' }}</p>
                <p><strong class="text-slate-700">Time:</strong> {{ $showtimeFormatted ? $showtimeFormatted->format('h:i A') : 'N/A' }}</p>
                <p><strong class="text-slate-700">Price per Ticket:</strong> LKR {{ number_format(data_get($booking, 'showtime.ticket_price', 0), 2) }}</p>
                <p><strong class="text-slate-700">Tickets:</strong> {{ data_get($booking, 'number_of_tickets') }}</p>
                <p><strong class="text-slate-700">Seats:</strong> {{ data_get($booking, 'seats', 'N/A') }}</p>
                <p><strong class="text-slate-700">Booking Date:</strong> {{ data_get($booking, 'created_at') ? data_get($booking, 'created_at')->format('M d, Y') : 'N/A' }}</p>
            </div>
        </div>

        <div class="flex flex-col justify-center items-start md:items-center">
            <p class="text-[10px] md:text-xs text-slate-500 uppercase mb-1">Total Amount</p>
            <p class="text-2xl md:text-3xl font-bold text-[#020617] mb-4">LKR {{ number_format(data_get($booking, 'total_price', 0), 2) }}</p>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full {{ $dotColor }}"></div>
                <span class="font-semibold {{ $statusColor }}">{{ $statusLabel }}</span>
            </div>
        </div>

        <div class="flex flex-col gap-3 justify-center">
            <button type="button" onclick="alert('Ticket Details:\n\nMovie: {{ data_get($booking, 'showtime.movie.title') }}\nTheatre: {{ data_get($booking, 'showtime.theatre.name') }}\nDate: {{ $showtimeFormatted ? $showtimeFormatted->format('M d, Y') : 'N/A' }}\nTime: {{ $showtimeFormatted ? $showtimeFormatted->format('h:i A') : 'N/A' }}\nSeats: {{ data_get($booking, 'seats') }}\nTotal: LKR {{ number_format(data_get($booking, 'total_price', 0), 2) }}')" class="w-full py-2.5 px-4 rounded-lg border-2 border-[#020617] text-[#020617] font-bold bg-transparent cursor-pointer transition-all hover:bg-[#020617]/10">
                View Details
            </button>
            @if (! $isPast)
                @if ($booking->status === 'pending')
                    <a href="{{ route('bookings.payment', $booking->id) }}" class="w-full py-2.5 px-4 rounded-lg bg-[#6482AD] text-white font-bold text-center no-underline transition-all">
                        Complete Payment
                    </a>
                @endif
                <a href="{{ route('bookings.cancel', $booking->id) }}" class="w-full py-2.5 px-4 rounded-lg bg-[#020617] text-white font-bold text-center no-underline transition-all">
                    Cancel Booking
                </a>
            @endif
        </div>
    </div>
</div>
