<x-movie-layout>
    <section class="min-h-screen py-12 px-4 bg-[#F6F6F6] text-[#020617]">
        <div class="w-full mx-auto max-w-6xl px-4">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
                <div>
                    <a href="{{ route('admin.dashboard') }}"
                        class="inline-flex items-center gap-2 text-[#6482AD] hover:text-[#006989] transition-colors no-underline mb-4 font-bold">
                        &larr; Back to Dashboard
                    </a>
                    <h1 class="text-4xl font-bold text-[#020617] mb-2 md:mb-1">{{ $title ?? 'Booking Management' }}</h1>
                    <p class="text-slate-500">View all customer bookings and details</p>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-6 shadow-sm">
                    <p class="text-[#020617]/70 text-sm mb-2 font-bold uppercase tracking-wider">Total Bookings</p>
                    <p class="text-3xl font-extrabold text-[#020617]">{{ count($bookings) }}</p>
                </div>
                <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-6 shadow-sm">
                    <p class="text-[#020617]/70 text-sm mb-2 font-bold uppercase tracking-wider">Confirmed</p>
                    <p class="text-3xl font-extrabold text-green-700">
                        {{ collect($bookings)->where('status', 'confirmed')->count() }}
                    </p>
                </div>
                <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-6 shadow-sm">
                    <p class="text-[#020617]/70 text-sm mb-2 font-bold uppercase tracking-wider">Pending Cancellations</p>
                    <p class="text-3xl font-extrabold text-orange-700">
                        {{ collect($bookings)->where('status', 'cancellation_requested')->count() }}
                    </p>
                </div>
                <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-6 shadow-sm">
                    <p class="text-[#020617]/70 text-sm mb-2 font-bold uppercase tracking-wider">Cancelled</p>
                    <p class="text-3xl font-extrabold text-red-700">
                        {{ collect($bookings)->where('status', 'cancelled')->count() }}
                    </p>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-6 md:p-8 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <!-- Desktop Table -->
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-[#020617]/10">
                                <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">
                                    Booking ID</th>
                                <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">
                                    Customer</th>
                                <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">
                                    Movie</th>
                                <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">
                                    Time</th>
                                <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">
                                    Theatre</th>
                                <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">
                                    Seats</th>
                                <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">
                                    Tickets</th>
                                <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">
                                    Amount</th>
                                <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">
                                    Date</th>
                                <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5">
                            @foreach ($bookings as $booking)
                                <tr class="transition-colors hover:bg-black/5 group">
                                    <td class="py-4 px-3 text-[#020617]/70 font-bold uppercase">
                                        #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-4 px-3">
                                        <div>
                                            <p class="text-[#020617] font-bold">{{ $booking->user->name }}</p>
                                            <p class="text-[10px] text-[#020617]/60 font-medium tracking-tight">
                                                {{ $booking->user->email }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="py-4 px-3 text-[#020617] font-bold">{{ $booking->showtime->movie->title }}
                                    </td>
                                    <td class="py-4 px-3 text-[#020617] font-bold">
                                        {{ \Carbon\Carbon::parse($booking->showtime->showtime)->format('h:i A') }}
                                    </td>
                                    <td class="py-4 px-3 text-[#020617]/80 font-bold">
                                        {{ $booking->showtime->theatre->name }}
                                    </td>
                                    <td class="py-4 px-3 text-[#020617] font-bold">{{ $booking->seats }}</td>
                                    <td class="py-4 px-3 text-[#020617] font-extrabold">{{ $booking->number_of_tickets }}
                                    </td>
                                    <td class="py-4 px-3 text-[#006989] font-extrabold text-base">LKR
                                        {{ number_format($booking->total_price, 2) }}
                                    </td>
                                    <td class="py-4 px-3 text-[#020617]/50 text-[10px] uppercase font-bold">
                                        {{ $booking->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="py-4 px-3">
                                        <x-booking-status-badge :status="$booking->status" :admin="true" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</x-movie-layout>