<x-movie-layout>
    <section class="min-h-screen py-12 px-4 bg-[#F6F6F6] text-[#020617]">
        <div class="w-full mx-auto max-w-7xl px-4 pt-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-[#020617] mb-2 md:mb-1">Admin Dashboard</h1>
                    <p class="text-slate-500 text-sm md:text-base">Welcome back, Admin! Here's your business overview.
                    </p>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-slate-500 text-xs md:text-sm">Last updated: {{ now()->format('M d, Y h:i A') }}</p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Bookings -->
                <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-6 shadow-sm">
                    <p class="text-slate-500 text-sm mb-1 uppercase font-bold tracking-wider">Total Bookings</p>
                    <p class="text-3xl font-extrabold text-[#020617]">{{ $stats['total_bookings'] }}</p>
                </div>

                <!-- Total Revenue -->
                <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-6 shadow-sm">
                    <p class="text-slate-500 text-sm mb-1 uppercase font-bold tracking-wider">Total Revenue</p>
                    <p class="text-3xl font-extrabold text-[#006989]">LKR
                        {{ number_format($stats['total_revenue'], 2) }}
                    </p>
                </div>

                <!-- Cancellations -->
                <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-6 shadow-sm">
                    <p class="text-slate-500 text-sm mb-1 uppercase font-bold tracking-wider">Cancellations</p>
                    <p class="text-3xl font-extrabold text-[#020617]">{{ $stats['total_cancellations'] }}</p>
                </div>

                <!-- Active Theatres -->
                <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-6 shadow-sm">
                    <p class="text-slate-500 text-sm mb-1 uppercase font-bold tracking-wider">Active Theatres</p>
                    <p class="text-3xl font-extrabold text-[#020617]">{{ $stats['active_theatres'] }}</p>
                </div>
            </div>

            <!-- Management Panels -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Theatre Management -->
                <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-[#020617]">Theatre Management</h3>
                        <a href="{{ route('theatres') }}"
                            class="text-[#006989] font-bold text-sm transition-colors hover:text-[#6482AD]">View All
                            →</a>
                    </div>
                    <div class="space-y-4 mb-8">
                        @foreach($theatres as $theatre)
                            <div class="flex justify-between items-center p-3 rounded-xl hover:bg-black/5">
                                <div>
                                    <p class="text-[#020617] font-bold">{{ $theatre->name }}</p>
                                    <p class="text-xs text-slate-600 font-medium">{{ $theatre->location }} •
                                        {{ $theatre->total_seats }} seats
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Movie Management -->
                <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-[#020617]">Movie Management</h3>
                        <a href="#" class="text-[#006989] font-bold text-sm transition-colors hover:text-[#6482AD]">View
                            All →</a>
                    </div>
                    <div class="space-y-4 mb-8">
                        @foreach($movies as $movie)
                            <div class="flex justify-between items-center p-3 rounded-xl hover:bg-black/5">
                                <div>
                                    <p class="text-[#020617] font-bold">{{ $movie->title }}</p>
                                    <p class="text-xs text-slate-600 font-medium">{{ $movie->genre }} • {{ $movie->rating }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-8 shadow-sm mb-8 overflow-hidden">
                <h3 class="text-xl font-bold text-[#020617] mb-8">Recent Bookings</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-black/10">
                                <th class="py-4 px-4 text-slate-900 font-bold uppercase tracking-wider text-[10px]">
                                    Booking ID</th>
                                <th class="py-4 px-4 text-slate-900 font-bold uppercase tracking-wider text-[10px]">
                                    Customer</th>
                                <th class="py-4 px-4 text-slate-900 font-bold uppercase tracking-wider text-[10px]">
                                    Movie</th>
                                <th class="py-4 px-4 text-slate-900 font-bold uppercase tracking-wider text-[10px]">
                                    Amount</th>
                                <th class="py-4 px-4 text-slate-900 font-bold uppercase tracking-wider text-[10px]">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5">
                            @foreach($recentBookings as $booking)
                                <tr class="hover:bg-black/5">
                                    <td class="py-4 px-4 font-medium text-slate-900">
                                        #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-4 px-4">
                                        <p class="text-[#020617] font-bold">{{ $booking->user->name }}</p>
                                        <p class="text-[10px] text-slate-800">{{ $booking->user->email }}</p>
                                    </td>
                                    <td class="py-4 px-4 text-[#020617] font-semibold">
                                        {{ $booking->showtime->movie->title }}
                                    </td>
                                    <td class="py-4 px-4 text-[#006989] font-bold">LKR
                                        {{ number_format($booking->total_price, 2) }}
                                    </td>
                                    <td class="py-4 px-4">
                                        <x-booking-status-badge :status="$booking->status" :admin="true" soft />
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