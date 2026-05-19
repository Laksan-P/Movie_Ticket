<x-movie-layout>
<script>
    function toggleForm() {
        const form = document.getElementById('showtime-form');
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
</script>

<section class="min-h-screen py-12 px-4 bg-[#F6F6F6] text-[#020617]">
    <div class="w-full mx-auto max-w-6xl px-4">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-[#6482AD] hover:text-[#006989] transition-colors no-underline mb-4 font-bold">
                    &larr; Back to Dashboard
                </a>
                <h1 class="text-4xl font-bold text-[#020617] mb-2 md:mb-1">Showtime Management</h1>
                <p class="text-slate-500">Manage movie showtimes and availability</p>
            </div>
            <button onclick="toggleForm()" class="w-full md:w-auto py-3 px-8 rounded-xl bg-[#6482AD] text-white font-bold transition-all hover:bg-[#006989] shadow-md active:scale-95 border-none cursor-pointer">
                + Add Showtime
            </button>
        </div>

        <!-- Add Showtime Form -->
        <div id="showtime-form" class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-8 shadow-sm mb-8" style="display: none;">
            <h3 class="text-xl font-bold text-[#020617] mb-6">Add New Showtime</h3>
            <form method="POST" action="{{ route('admin.showtimes.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="theatre_id" class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Theatre</label>
                        <div class="relative">
                            <select id="theatre_id" name="theatre_id" required
                                class="admin-select w-full bg-white border border-gray-200 rounded-xl p-4 pr-12 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium cursor-pointer">
                                <option value="" disabled {{ old('theatre_id') ? '' : 'selected' }}>Select Theatre</option>
                                @forelse ($theatres as $theatre)
                                    <option value="{{ $theatre->id }}" @selected(old('theatre_id') == $theatre->id)>{{ $theatre->name }} — {{ $theatre->location }}</option>
                                @empty
                                    <option value="" disabled>No theatres available</option>
                                @endforelse
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-[#020617]/50" aria-hidden="true">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </div>
                        @if($theatres->isEmpty())
                            <p class="mt-2 text-xs font-semibold text-amber-900">Add a theatre first from <a href="{{ route('admin.theatres.index') }}" class="underline">Theatre Management</a>.</p>
                        @endif
                    </div>
                    <div>
                        <label for="movie_id" class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Movie</label>
                        <div class="relative">
                            <select id="movie_id" name="movie_id" required
                                class="admin-select w-full bg-white border border-gray-200 rounded-xl p-4 pr-12 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium cursor-pointer">
                                <option value="" disabled {{ old('movie_id') ? '' : 'selected' }}>Select Movie</option>
                                @forelse ($movies as $movie)
                                    <option value="{{ $movie->id }}" @selected(old('movie_id') == $movie->id)>{{ $movie->title }}</option>
                                @empty
                                    <option value="" disabled>No movies available</option>
                                @endforelse
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-[#020617]/50" aria-hidden="true">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </div>
                        @if($movies->isEmpty())
                            <p class="mt-2 text-xs font-semibold text-amber-900">Add a movie first from <a href="{{ route('admin.movies.index') }}" class="underline">Movie Management</a>.</p>
                        @endif
                    </div>
                    <div>
                        <label for="showtime" class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Date & Time</label>
                        <input type="datetime-local" id="showtime" name="showtime" required class="w-full bg-white border border-gray-200 rounded-xl p-4 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium">
                    </div>
                    <div>
                        <label for="ticket_price" class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Ticket Price (LKR)</label>
                        <input type="number" id="ticket_price" name="ticket_price" required class="w-full bg-white border border-gray-200 rounded-xl p-4 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium" min="0" step="0.01">
                    </div>
                    <div>
                        <label for="format" class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Format</label>
                        <div class="relative">
                            <select id="format" name="format" class="admin-select w-full bg-white border border-gray-200 rounded-xl p-4 pr-12 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium cursor-pointer">
                                <option value="2D">2D</option>
                                <option value="3D">3D</option>
                                <option value="IMAX">IMAX</option>
                                <option value="4DX">4DX</option>
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-[#020617]/50" aria-hidden="true">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div>
                        <label for="language" class="block text-sm font-bold text-[#020617]/70 mb-2 uppercase tracking-wide">Language</label>
                        <div class="relative">
                            <select id="language" name="language" class="admin-select w-full bg-white border border-gray-200 rounded-xl p-4 pr-12 text-[#020617] outline-none focus:border-[#6482AD] transition-all font-medium cursor-pointer">
                                <option value="English">English</option>
                                <option value="Hindi">Hindi</option>
                                <option value="Tamil">Tamil</option>
                                <option value="Telugu">Telugu</option>
                                <option value="Malayalam">Malayalam</option>
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-[#020617]/50" aria-hidden="true">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 py-4 px-6 rounded-xl bg-[#6482AD] text-white font-bold transition-all hover:bg-[#006989] shadow-sm border-none cursor-pointer">
                        Create Showtime
                    </button>
                    <button type="button" onclick="toggleForm()" class="flex-1 py-4 px-6 rounded-xl border-2 border-[#6482AD] text-[#6482AD] bg-transparent font-bold transition-all hover:bg-[#6482AD]/5 active:scale-95 cursor-pointer">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-600 rounded-lg p-4 mb-8">
                <p class="text-sm text-green-700 font-bold m-0">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Showtimes Table -->
        <div class="bg-[#B0B7B3] border border-gray-200 rounded-xl p-6 md:p-8 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-[#020617]/10">
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Theatre</th>
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Movie</th>
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Format/Lang</th>
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Date & Time</th>
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Availability</th>
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Price</th>
                            <th class="py-4 px-3 text-[#020617]/70 font-bold uppercase tracking-wider text-[10px]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5">
                        @foreach ($showtimes as $showtime)
                            <tr class="transition-colors hover:bg-black/5 group">
                                <td class="py-4 px-3 text-[#020617] font-bold">{{ $showtime->theatre->name }}</td>
                                <td class="py-4 px-3 text-[#020617] font-bold">{{ $showtime->movie->title }}</td>
                                <td class="py-4 px-3">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[#020617]/80 font-bold text-xs uppercase tracking-tight">{{ $showtime->format }}</span>
                                        <span class="text-[#020617]/30 font-bold">•</span>
                                        <span class="text-[#020617]/80 font-bold text-xs uppercase tracking-tight">{{ $showtime->language }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-3 text-[#020617]/60 font-medium">{{ \Carbon\Carbon::parse($showtime->showtime)->format('M d, Y h:i A') }}</td>
                                <td class="py-4 px-3">
                                    <span class="inline-block px-3 py-1 rounded-md {{ $showtime->available_seats > 20 ? 'bg-green-700 text-white' : 'bg-red-700 text-white' }} text-[10px] font-bold tracking-tight">
                                        {{ $showtime->available_seats }} Seats
                                    </span>
                                </td>
                                <td class="py-4 px-3 text-[#006989] font-extrabold">LKR {{ number_format($showtime->ticket_price, 2) }}</td>
                                <td class="py-4 px-3">
                                    <x-admin-action-dropdown>
                                        <form action="{{ route('admin.showtimes.destroy', $showtime->id) }}" method="POST" onsubmit="return confirm('Are you sure?')" class="block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" role="menuitem" class="block w-full text-left px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 transition-colors border-none cursor-pointer bg-transparent">
                                                Delete
                                            </button>
                                        </form>
                                    </x-admin-action-dropdown>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<style>
    .admin-select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-color: #fff;
    }
</style>
</x-movie-layout>
