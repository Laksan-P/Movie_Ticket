<x-movie-layout>
@php
    $formats = explode(',', $movie->formats ?? '2D');
    $languages = explode(',', $movie->languages ?? 'English');
    $cast = $movie->cast ?? [];
    $crew = $movie->crew ?? [];
@endphp

<!-- Movie Hero Section -->
<section class="relative w-full overflow-hidden bg-[#F6F6F6] pb-8 md:h-[500px] md:pb-0">
    <div class="container mx-auto px-4 relative z-20 h-full flex items-center">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6 md:gap-8 pt-4 md:pt-10 w-full">
            <!-- Poster / Trailer Box -->
            <div class="w-40 md:w-64 flex-shrink-0">
                <div class="relative rounded-xl overflow-hidden shadow-2xl border-2 border-amber-500/20 aspect-[2/3] bg-[#111827] group">
                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110" 
                         style="background-image: url('{{ asset($movie->image) }}');">
                    </div>
                    <div class="absolute inset-0 bg-black/40 transition-colors duration-300 group-hover:bg-black/20"></div>
                     @if($movie->trailer_url)
                        <a href="{{ $movie->trailer_url }}" target="_blank" class="absolute inset-0 flex items-center justify-center z-10 cursor-pointer">
                            <div class="w-16 h-16 rounded-full bg-red-600/90 flex items-center justify-center shadow-lg transition-transform duration-300 hover:scale-110">
                                <svg class="w-9 h-9 ml-1" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </a>
                    @else
                        <div class="absolute inset-0 flex items-center justify-center z-10 cursor-default">
                             <span class="text-6xl drop-shadow-md">🎬</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Details -->
            <div class="flex-1 text-center md:text-left text-[#020617]">
                <h1 class="text-3xl md:text-6xl font-extrabold mb-2 tracking-tight leading-tight">{{ $movie->title }}</h1>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-3 mb-6 text-sm md:text-base items-center">
                     <span class="text-amber-400 font-bold">⭐ {{ $movie->rating }}</span>
                     <span class="text-gray-400">•</span>
                     <span>{{ $movie->duration }} mins</span>
                     <span class="text-gray-400">•</span>
                     <span class="text-red-500 font-semibold">{{ $movie->genre }}</span>
                     <span class="text-gray-400">•</span>
                     <span>{{ \Carbon\Carbon::parse($movie->release_date)->format('M d, Y') }}</span>
                </div>

                <div class="flex flex-wrap justify-center md:justify-start gap-2 mb-6">
                    @foreach($formats as $fmt)
                        <span class="px-3 py-1 rounded-full border border-gray-200 text-[10px] md:text-xs text-slate-500 font-bold uppercase tracking-wider bg-white shadow-sm">{{ trim($fmt) }}</span>
                    @endforeach
                    @foreach($languages as $lang)
                        <span class="px-3 py-1 rounded-full bg-[#006989] text-[10px] md:text-xs text-white font-bold">{{ trim($lang) }}</span>
                    @endforeach
                </div>

                <button onclick="openFormatModal()" class="inline-flex items-center gap-2 py-4 px-8 rounded-xl bg-[#006989] text-white font-bold transition-all duration-300 hover:bg-[#6482AD] hover:shadow-lg hover:-translate-y-1 border-none cursor-pointer">
                    Book Tickets →
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Format Selection Modal -->
<div id="formatModal" class="fixed inset-0 z-[100] hidden opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white p-8 rounded-2xl w-full max-w-md shadow-2xl transition-transform duration-300 scale-95" id="modalContent">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-[#020617]">Select Format</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-[#020617] transition-colors bg-transparent border-none cursor-pointer p-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="space-y-8">
            <div>
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Language</h4>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($languages as $lang)
                        <button onclick="selectOption(this, 'lang')" class="modal-option-btn py-3 px-4 rounded-xl border border-gray-200 text-[#020617] font-semibold bg-white cursor-pointer transition-all duration-300 hover:border-[#006989]" data-value="{{ trim($lang) }}">
                            {{ trim($lang) }}
                        </button>
                    @endforeach
                </div>
            </div>
            <div>
                 <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Format</h4>
                 <div class="grid grid-cols-2 gap-4">
                    @foreach($formats as $fmt)
                        <button onclick="selectOption(this, 'format')" class="modal-option-btn py-3 px-4 rounded-xl border border-gray-200 text-[#020617] font-semibold bg-white cursor-pointer transition-all duration-300 hover:border-[#006989]" data-value="{{ trim($fmt) }}">
                            {{ trim($fmt) }}
                        </button>
                    @endforeach
                </div>
            </div>
            <button onclick="applyModalFilters()" class="w-full py-4 mt-4 rounded-xl bg-[#006989] text-white font-bold text-lg transition-all duration-300 hover:bg-[#6482AD] hover:shadow-xl border-none cursor-pointer">
                Proceed
            </button>
        </div>
    </div>
</div>

<!-- Content Grid -->
<section class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
        <div class="md:col-span-1 space-y-10">
            <div>
                <h3 class="text-[#020617] font-bold text-xl mb-4 border-b-2 border-[#020617] w-fit pb-1">Synopsis</h3>
                <p class="text-slate-600 text-sm leading-relaxed">{{ $movie->description }}</p>
            </div>
            @if(!empty($cast))
            <div>
                <h3 class="text-[#020617] font-bold text-xl mb-4 border-b-2 border-[#020617] w-fit pb-1">Cast</h3>
                <div class="flex overflow-x-auto gap-4 pb-4 scrollbar-hide">
                    @foreach($cast as $actor)
                    <div class="flex-shrink-0 w-24 text-center">
                        <div class="w-16 h-16 mx-auto mb-2 rounded-full bg-gray-200 overflow-hidden border border-amber-500/20 shadow-sm">
                            @if(isset($actor['image']) && $actor['image'])
                                <img src="{{ $actor['image'] }}" alt="{{ $actor['name'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-xs text-gray-500 bg-gray-100">👤</div>
                            @endif
                        </div>
                        <p class="text-[#020617] text-[10px] font-bold truncate">{{ $actor['name'] }}</p>
                        <p class="text-slate-500 text-[9px] font-medium truncate">{{ $actor['role'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="md:col-span-2" id="showtimes">
            <div class="md:sticky md:top-20 bg-white z-30 p-5 rounded-xl border border-gray-200 shadow-sm mb-8">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-4">
                    <select id="filter-date" class="w-full bg-white text-[#020617] border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none" onchange="filterShowtimes()">
                        <option value="all">All Dates</option>
                        @for ($i = 0; $i < 5; $i++)
                            @php
                                $ts = strtotime("+$i days");
                                $dateVal = date('Y-m-d', $ts);
                                $label = date('D, d M', $ts);
                                if ($i == 0) $label = "Today, " . date('d M', $ts);
                                if ($i == 1) $label = "Tomorrow, " . date('d M', $ts);
                            @endphp
                            <option value="{{ $dateVal }}">{{ $label }}</option>
                        @endfor
                    </select>
                    <select id="filter-lang" class="w-full bg-white text-[#020617] border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none" onchange="filterShowtimes()">
                        <option value="all">Language</option>
                        @foreach($languages as $lang)
                            <option value="{{ trim($lang) }}">{{ trim($lang) }}</option>
                        @endforeach
                    </select>
                    <select id="filter-format" class="w-full bg-white text-[#020617] border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none" onchange="filterShowtimes()">
                        <option value="all">Format</option>
                        @foreach($formats as $fmt)
                            <option value="{{ trim($fmt) }}">{{ trim($fmt) }}</option>
                        @endforeach
                    </select>

                    <select id="filter-price" class="w-full bg-white text-[#020617] border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none" onchange="filterShowtimes()">
                        <option value="all">Price Range</option>
                        <option value="0-500">Below 500</option>
                        <option value="500-1000">500 - 1000</option>
                        <option value="1000+">Above 1000</option>
                    </select>

                    <select id="filter-timing" class="w-full bg-white text-[#020617] border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none" onchange="filterShowtimes()">
                        <option value="all">Show Timings</option>
                        <option value="morning">Morning (Before 12PM)</option>
                        <option value="afternoon">Afternoon (12PM - 4PM)</option>
                        <option value="evening">Evening (4PM - 8PM)</option>
                        <option value="night">Night (After 8PM)</option>
                    </select>
                </div>
            </div>

            <div class="space-y-6">
                @foreach ($theatres as $theatreId => $data)
                    <div class="theatre-row bg-white border rounded-2xl border-gray-200 p-8 shadow-sm transition-all hover:shadow-md">
                        <div class="flex flex-col md:flex-row justify-between gap-4 mb-6 pb-4 border-b border-gray-100">
                            <div>
                                <h3 class="text-[#020617] font-bold text-xl">{{ $data['details']['name'] }}</h3>
                                <p class="text-sm text-slate-500 mt-1">{{ $data['details']['location'] }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-4">
                            @foreach ($data['showtimes'] as $st)
                                <a href="{{ route('bookings.create', $st->id) }}" 
                                   class="showtime-btn flex flex-col items-center justify-center w-24 h-12 border rounded transition-all duration-200 no-underline text-green-500 border-green-500/30 font-bold hover:bg-[#006989] hover:text-white"
                                   data-date="{{ \Carbon\Carbon::parse($st->showtime)->format('Y-m-d') }}"
                                   data-language="{{ $st->language }}"
                                   data-format="{{ $st->format }}"
                                   data-price="{{ $st->ticket_price }}"
                                   data-hour="{{ \Carbon\Carbon::parse($st->showtime)->format('H') }}">
                                    <span class="text-sm">{{ \Carbon\Carbon::parse($st->showtime)->format('h:i A') }}</span>
                                    <span class="text-[10px]">{{ $st->format }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
    function openFormatModal() {
        document.getElementById('formatModal').classList.remove('hidden');
        setTimeout(() => document.getElementById('formatModal').classList.remove('opacity-0'), 10);
    }
    function closeModal() {
        document.getElementById('formatModal').classList.add('opacity-0');
        setTimeout(() => document.getElementById('formatModal').classList.add('hidden'), 300);
    }
    function selectOption(btn, type) {
        document.querySelectorAll(`.modal-option-btn[onclick*="${type}"]`).forEach(b => b.classList.remove('bg-[#020617]', 'text-white'));
        btn.classList.add('bg-[#020617]', 'text-white');
    }
    function applyModalFilters() {
        const selectedLang = document.querySelector('.modal-option-btn[onclick*="lang"].bg-\\[\\#020617\\]');
        const selectedFmt = document.querySelector('.modal-option-btn[onclick*="format"].bg-\\[\\#020617\\]');
        
        if (selectedLang) {
            document.getElementById('filter-lang').value = selectedLang.getAttribute('data-value');
        }
        if (selectedFmt) {
            document.getElementById('filter-format').value = selectedFmt.getAttribute('data-value');
        }
        
        filterShowtimes();
        closeModal();
        
        document.getElementById('showtimes').scrollIntoView({ behavior: 'smooth' });
    }

    function filterShowtimes() {
        const date = document.getElementById('filter-date').value;
        const lang = document.getElementById('filter-lang').value;
        const format = document.getElementById('filter-format').value;
        const priceRange = document.getElementById('filter-price').value;
        const timing = document.getElementById('filter-timing').value;

        document.querySelectorAll('.showtime-btn').forEach(btn => {
            const matchesDate = date === 'all' || btn.getAttribute('data-date') === date;
            const matchesLang = lang === 'all' || btn.getAttribute('data-language') === lang;
            const matchesFmt = format === 'all' || btn.getAttribute('data-format') === format;

            const price = parseFloat(btn.getAttribute('data-price'));
            let matchesPrice = priceRange === 'all';
            if (priceRange === '0-500') matchesPrice = price < 500;
            else if (priceRange === '500-1000') matchesPrice = price >= 500 && price <= 1000;
            else if (priceRange === '1000+') matchesPrice = price > 1000;

            const hour = parseInt(btn.getAttribute('data-hour'));
            let matchesTiming = timing === 'all';
            if (timing === 'morning') matchesTiming = hour < 12;
            else if (timing === 'afternoon') matchesTiming = hour >= 12 && hour < 16;
            else if (timing === 'evening') matchesTiming = hour >= 16 && hour < 20;
            else if (timing === 'night') matchesTiming = hour >= 20;

            if (matchesDate && matchesLang && matchesFmt && matchesPrice && matchesTiming) {
                btn.style.display = 'flex';
            } else {
                btn.style.display = 'none';
            }
        });

        document.querySelectorAll('.theatre-row').forEach(row => {
            const hasVisible = Array.from(row.querySelectorAll('.showtime-btn')).some(b => b.style.display !== 'none');
            row.style.display = hasVisible ? 'block' : 'none';
        });
    }
</script>
</x-movie-layout>
