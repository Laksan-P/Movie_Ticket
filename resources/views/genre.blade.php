<x-movie-layout>
<div class="min-h-screen py-8 md:py-12 px-4 bg-[#020617]">
    <div class="w-full mx-auto max-w-7xl px-4">
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-800">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2 capitalize">{{ $genre }} Movies</h1>
                <p class="text-slate-400">Browse our collection of {{ strtolower($genre) }} films.</p>
            </div>
            <a href="{{ route('home') }}" class="text-sm text-amber-400 hover:text-white transition-colors no-underline">← Back to Home</a>
        </div>

        @if (count($movies) === 0)
            <div class="text-center py-20">
                <div class="text-6xl mb-4">🎬</div>
                <h2 class="text-xl font-bold text-white mb-2">No {{ $genre }} movies found</h2>
                <p class="text-slate-400">Check back later for new releases!</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($movies as $movie)
                    <a href="{{ route('movies.show', $movie->id) }}" class="group block relative rounded-xl overflow-hidden bg-gray-900 border border-gray-800 shadow-lg transition-all hover:border-amber-400 hover:-translate-y-1 no-underline">
                        <!-- Movie Poster -->
                        <div class="relative aspect-[2/3] bg-gray-900 overflow-hidden">
                            <img src="{{ asset($movie->image) }}" alt="{{ $movie->title }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <!-- Play Icon Overlay -->
                            <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                 <div class="w-12 h-12 rounded-full bg-red-600 flex items-center justify-center shadow-lg transform scale-0 transition-transform duration-300 group-hover:scale-100">
                                    <svg class="w-7 h-7 ml-1" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black to-transparent">
                                <span class="inline-block px-2 py-1 bg-amber-400 text-black text-xs font-bold rounded">
                                    {{ $movie->rating }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Movie Info -->
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-white mb-1 truncate">{{ $movie->title }}</h3>
                            <div class="flex items-center gap-2 text-xs text-slate-400 mb-3">
                                 <span>{{ $movie->duration }} mins</span>
                                 <span>•</span>
                                 <span>{{ $movie->genre }}</span>
                            </div>
                             <div class="w-full py-2 bg-gray-800 text-center text-amber-400 text-sm font-bold rounded transition-all group-hover:bg-amber-400 group-hover:text-black">
                                Book Now
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
</x-movie-layout>
