<x-movie-layout>
<section class="min-h-screen py-6 md:py-12 px-4 bg-[#F6F6F6]">
    <div class="w-full mx-auto max-w-6xl">
        <div class="mb-8 flex items-end justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#020617] mb-1">{{ $showtime->movie->title }}</h1>
                <p class="text-sm text-slate-500">
                    {{ $showtime->theatre->name }} |
                    {{ \Carbon\Carbon::parse($showtime->showtime)->format('M d, h:i A') }}
                </p>
            </div>
        </div>

        @livewire('seat-selection', ['showtime' => $showtime])
    </div>
</section>
</x-movie-layout>
