<x-movie-layout>
    <section class="min-h-screen py-12 px-4 bg-[#F6F6F6] text-[#020617]">
        <div class="w-full mx-auto max-w-6xl px-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
                <div>
                    <a href="{{ route('admin.dashboard') }}"
                        class="inline-flex items-center gap-2 text-[#6482AD] hover:text-[#006989] transition-colors no-underline mb-4 font-bold">
                        &larr; Back to Dashboard
                    </a>
                    <h1 class="text-4xl font-bold text-[#020617] mb-2">Cancellation Management</h1>
                    <p class="text-slate-500">Review pending cancellation requests and history</p>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-xl border border-green-300 bg-green-50 p-4 text-sm font-semibold text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 rounded-xl border border-red-300 bg-red-50 p-4 text-sm font-semibold text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-6 shadow-sm">
                    <p class="text-[#020617]/70 text-sm mb-2 font-bold uppercase tracking-wider">Pending Requests</p>
                    <p class="text-3xl font-extrabold text-orange-700">{{ $pendingCancellations->count() }}</p>
                </div>
                <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-6 shadow-sm">
                    <p class="text-[#020617]/70 text-sm mb-2 font-bold uppercase tracking-wider">Cancelled</p>
                    <p class="text-3xl font-extrabold text-red-700">{{ $cancelledBookings->count() }}</p>
                </div>
            </div>

            <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-6 md:p-8 shadow-sm mb-8">
                <h2 class="text-xl font-bold text-[#020617] mb-6">Pending Cancellation Requests</h2>
                @if ($pendingCancellations->isEmpty())
                    <p class="text-slate-600 font-medium">No pending cancellation requests.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="border-b border-[#020617]/10">
                                    <th class="py-4 px-3 text-[10px] font-bold uppercase text-[#020617]/70">Booking</th>
                                    <th class="py-4 px-3 text-[10px] font-bold uppercase text-[#020617]/70">Customer</th>
                                    <th class="py-4 px-3 text-[10px] font-bold uppercase text-[#020617]/70">Movie</th>
                                    <th class="py-4 px-3 text-[10px] font-bold uppercase text-[#020617]/70">Reason</th>
                                    <th class="py-4 px-3 text-[10px] font-bold uppercase text-[#020617]/70">Status</th>
                                    <th class="py-4 px-3 text-[10px] font-bold uppercase text-[#020617]/70">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/5">
                                @foreach ($pendingCancellations as $booking)
                                    <tr class="hover:bg-black/5">
                                        <td class="py-4 px-3 font-bold">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</td>
                                        <td class="py-4 px-3">
                                            <p class="font-bold">{{ $booking->user->name }}</p>
                                            <p class="text-[10px] text-slate-600">{{ $booking->user->email }}</p>
                                        </td>
                                        <td class="py-4 px-3 font-bold">{{ $booking->showtime->movie->title }}</td>
                                        <td class="py-4 px-3 text-xs max-w-xs">{{ $booking->cancellation?->reason ?? '—' }}</td>
                                        <td class="py-4 px-3">
                                            <x-booking-status-badge :status="$booking->status" :admin="true" />
                                        </td>
                                        <td class="py-4 px-3">
                                            <div class="flex flex-wrap gap-2">
                                                <form method="POST" action="{{ route('admin.cancellations.approve', $booking) }}">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-green-700 text-white text-xs font-bold">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.cancellations.reject', $booking) }}">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-slate-700 text-white text-xs font-bold">
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-6 md:p-8 shadow-sm">
                <h2 class="text-xl font-bold text-[#020617] mb-6">Cancellation History</h2>
                @if ($cancelledBookings->isEmpty())
                    <p class="text-slate-600 font-medium">No cancelled bookings yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="border-b border-[#020617]/10">
                                    <th class="py-4 px-3 text-[10px] font-bold uppercase text-[#020617]/70">Booking</th>
                                    <th class="py-4 px-3 text-[10px] font-bold uppercase text-[#020617]/70">Customer</th>
                                    <th class="py-4 px-3 text-[10px] font-bold uppercase text-[#020617]/70">Movie</th>
                                    <th class="py-4 px-3 text-[10px] font-bold uppercase text-[#020617]/70">Refund</th>
                                    <th class="py-4 px-3 text-[10px] font-bold uppercase text-[#020617]/70">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/5">
                                @foreach ($cancelledBookings as $booking)
                                    <tr class="hover:bg-black/5">
                                        <td class="py-4 px-3 font-bold">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</td>
                                        <td class="py-4 px-3 font-bold">{{ $booking->user->name }}</td>
                                        <td class="py-4 px-3 font-bold">{{ $booking->showtime->movie->title }}</td>
                                        <td class="py-4 px-3 text-green-700 font-bold">
                                            LKR {{ number_format($booking->cancellation?->refund_amount ?? ($booking->total_price * 0.5), 2) }}
                                        </td>
                                        <td class="py-4 px-3">
                                            <x-booking-status-badge :status="$booking->status" :admin="true" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-movie-layout>
