<x-movie-layout>
<!-- Header -->
<section class="w-full py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">
            <span class="block text-[#020617]">Select Your Theatre &</span>
            <span class="block text-[#020617]">Choose Your Movie</span>
        </h1>
        <p class="text-lg text-slate-400 max-w-2xl">
            Browse available theatres and select from exciting movies with multiple showtimes
        </p>
    </div>
</section>

<!-- Main Content -->
<div class="w-full py-12 min-h-[80vh]">
    <div class="container mx-auto px-4">
        <div class="grid gap-8 lg:grid-cols-4">
            <!-- Theatres List -->
            <div class="lg:col-span-1">
                <h2 class="text-2xl font-bold mb-4 text-[#020617]">Theatres</h2>
                <div class="flex flex-col gap-3 lg:sticky lg:top-32">
                    @foreach ($theatres as $theatre)
                        @php $isSelected = request('theatre_id') == $theatre->id; @endphp
                        <a href="{{ route('theatres.index', ['theatre_id' => $theatre->id]) }}"
                            class="group block w-full text-left p-5 rounded-xl transition-all duration-300 {{ $isSelected ? 'bg-[#6482AD] shadow-lg shadow-[#020617]/10' : 'bg-white border border-gray-200 hover:shadow-md' }}">
                            <h3 class="font-bold {{ $isSelected ? 'text-white' : 'text-[#020617]' }} mb-2">{{ $theatre->name }}</h3>
                            <div class="text-sm {{ $isSelected ? 'text-white' : 'text-slate-500' }} flex flex-col gap-1">
                                <div>{{ $theatre->location }}</div>
                                <div class="text-xs font-medium">{{ $theatre->total_seats }} Seats</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Movies List -->
            <div class="lg:col-span-3">
                @if (!request('theatre_id'))
                    <div class="text-center py-20">
                        <h3 class="text-2xl font-bold text-black mb-2">Select a Theatre</h3>
                        <p class="text-gray-400">Choose a theatre from the list to view available movies</p>
                    </div>
                @else
                    @if ($selectedTheatre)
                        <!-- Theatre Info -->
                        <div class="mb-8 p-8 rounded-2xl bg-[#6482AD] text-white shadow-xl">
                             <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                                <div>
                                    <h2 class="text-3xl font-bold mb-2">{{ $selectedTheatre->name }}</h2>
                                    <div class="flex flex-col sm:flex-row gap-6 text-slate-300">
                                        <div>{{ $selectedTheatre->location }}</div>
                                        <div>{{ $selectedTheatre->total_seats }} Total Seats</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Showtimes -->
                        <h3 class="text-2xl font-bold text-[#020617] mb-6">Now Showing</h3>

                        @if ($uniqueMovies->isEmpty())
                            <div class="text-center py-10">
                                <p class="text-gray-400">No showtimes available for this theatre</p>
                            </div>
                        @else
                            <div class="grid gap-6 md:grid-cols-2">
                                @foreach ($uniqueMovies as $movieId => $stGroup)
                                    @php $movie = $stGroup->first()->movie; @endphp
                                    <div class="movie-card group bg-[#023047] border border-gray-100 rounded-2xl overflow-hidden flex flex-col h-full shadow-sm hover:shadow-xl transition-all duration-500 hover:-translate-y-1">
                                        <div class="relative aspect-video bg-gray-100 overflow-hidden">
                                            <img src="{{ asset($movie->image) }}" alt="{{ $movie->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                            <div class="absolute bottom-4 left-4 bg-black/70 text-white text-[10px] font-bold px-3 py-1 rounded-full backdrop-blur-md border border-white/20">
                                                {{ $movie->rating }}
                                            </div>
                                        </div>
                                        
                                        <div class="p-8 flex flex-col flex-1">
                                            <h4 class="text-xl font-bold text-white mb-3 group-hover:text-white transition-colors">
                                                {{ $movie->title }}
                                            </h4>
                                            <p class="text-sm text-white mb-8 flex-1 line-clamp-2 leading-relaxed">
                                                Experience {{ $movie->title }} at {{ $selectedTheatre->name }}.
                                            </p>
                                            
                                            <a href="{{ route('movies.show', ['movie' => $movie->id, 'theatre_id' => $selectedTheatre->id]) }}" class="w-full inline-flex items-center justify-center py-4 px-6 rounded-xl bg-[#006989] text-white font-bold text-sm transition-all duration-300 hover:bg-[#6482AD] hover:shadow-lg">
                                                Book Now →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
</x-movie-layout>
