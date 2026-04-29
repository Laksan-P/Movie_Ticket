<x-movie-layout>
<!-- HERO SECTION -->
<section class="relative bg-[#F6F6F6] py-16 md:py-28 overflow-hidden">
    <div class="max-w-[1440px] mx-auto px-6 grid gap-12 lg:gap-20 lg:grid-cols-2 lg:items-center">

        <!-- LEFT CONTENT -->
        <div class="relative z-10">
            <h1 class="text-4xl md:text-5xl font-extrabold text-black leading-tight">
                <span>Experience Cinema</span>
                <span class="bg-gradient-to-r from-[#6482AD] to-[#006989] bg-clip-text text-transparent">Your Way</span>
            </h1>

            <p class="my-6 text-black text-lg">
                Book your favourite movie tickets from premium medium-sized theatres.
                Enjoy seamless booking, instant confirmation, and flexible cancellation
                policies with 50% refund support.
            </p>

            <div class="flex items-center flex-wrap gap-4 mt-8 relative z-50">
                <a href="{{ route('theatres.index') }}" class="inline-block px-5 py-4 md:px-10 md:py-5 rounded-xl font-bold no-underline relative cursor-pointer text-sm md:text-base transition-all whitespace-nowrap bg-gradient-to-r from-[#006989] to-[#6482AD] text-white hover:shadow-lg active:scale-95 transition-transform">
                    Book Tickets Now →
                </a>
                @guest
                    <a href="{{ route('register') }}" class="inline-block px-5 py-4 md:px-10 md:py-5 rounded-xl font-bold no-underline relative cursor-pointer text-sm md:text-base transition-all whitespace-nowrap border-2 border-[#6482AD] text-[#6482AD] hover:bg-[#6482AD]/10 active:scale-95 transition-transform">
                        Create Account
                    </a>
                @endguest
            </div>
        </div>

        <!-- SLIDER -->
        <div class="movie-slider-wrapper relative w-full max-w-[800px] mx-auto group/slider">
            <div class="slider-viewport h-[450px] rounded-[2rem] overflow-hidden relative bg-[#0f172a] shadow-2xl">
                @if($sliderMovies->isNotEmpty())
                    @foreach($sliderMovies as $index => $movie)
                        <div class="slider-slide group absolute inset-0 transition-all duration-700 opacity-0 scale-100 invisible [&.is-active]:opacity-100 [&.is-active]:visible [&.is-active]:z-10 {{ $index === 0 ? 'is-active' : '' }}" data-index="{{ $index }}">
                            <!-- Background Image -->
                            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[2000ms] group-[.is-active]:scale-110"
                                 style="background-image:url('{{ asset($movie->image) }}')"></div>
                            
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

                            <!-- Content -->
                            <div class="absolute bottom-0 left-0 w-full p-10 z-20 translate-y-4 opacity-0 transition-all duration-500 delay-300 group-[.is-active]:translate-y-0 group-[.is-active]:opacity-100">
                                <span class="inline-block px-4 py-1.5 rounded-full bg-[#dc2626] text-white text-xs font-bold uppercase tracking-wider mb-3 shadow-lg">Now Showing</span>
                                <h3 class="text-5xl font-extrabold text-white mb-2 drop-shadow-md tracking-tight leading-none">{{ $movie->title }}</h3>
                                <p class="text-gray-300 text-lg font-medium tracking-wide">{{ $movie->genre }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="flex items-center justify-center h-full text-gray-400 text-lg">No Featured Movies</div>
                @endif

                <!-- Navigation Controls -->
                <button id="slider-prev-btn" class="absolute top-1/2 -translate-y-1/2 left-4 w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white flex items-center justify-center cursor-pointer transition-all hover:bg-white/20 hover:scale-110 z-30 opacity-0 group-hover/slider:opacity-100 border-none" aria-label="Previous Slide">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button id="slider-next-btn" class="absolute top-1/2 -translate-y-1/2 right-4 w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white flex items-center justify-center cursor-pointer transition-all hover:bg-white/20 hover:scale-110 z-30 opacity-0 group-hover/slider:opacity-100 border-none" aria-label="Next Slide">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Pagination Indicators -->
                <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex gap-2 z-30">
                    @foreach($sliderMovies as $index => $_)
                        <button class="pagination-dot {{ $index === 0 ? 'is-active' : '' }} w-8 h-1 rounded-full bg-white/30 border-none cursor-pointer transition-all duration-300 [&.is-active]:bg-white [&.is-active]:w-12 hover:bg-white/50"
                                data-index="{{ $index }}"></button>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</section>

<!-- FEATURES -->
<section class="bg-[#F6F6F6] py-16 md:py-24">
    <div class="max-w-[1440px] mx-auto px-6">
        <h2 class="text-3xl md:text-5xl font-extrabold mb-12 text-black text-center md:text-left leading-tight">
            Why Choose <span class="bg-gradient-to-r from-[#6482AD] to-[#006989] bg-clip-text text-transparent">MovieBuff</span>?
        </h2>

        <div class="grid gap-8 md:gap-10 md:grid-cols-3">
            <div class="bg-[#b3bec8] p-8 rounded-2xl text-black transition-all hover:-translate-y-1 flex flex-col items-center md:items-start text-center md:text-left min-h-[140px]">
                <h3 class="text-xl font-bold mb-2">Easy Booking</h3>
                <p class="text-gray-700">Book tickets in just 3 simple steps</p>
            </div>

            <div class="bg-[#b3bec8] p-8 rounded-2xl text-black transition-all hover:-translate-y-1 flex flex-col items-center md:items-start text-center md:text-left min-h-[140px]">
                <h3 class="text-xl font-bold mb-2">Multiple Theatres</h3>
                <p class="text-gray-700">Choose from quality theatres</p>
            </div>

            <div class="bg-[#b3bec8] p-8 rounded-2xl text-black transition-all hover:-translate-y-1 flex flex-col items-center md:items-start text-center md:text-left min-h-[140px]">
                <h3 class="text-xl font-bold mb-2">Flexible Cancellation</h3>
                <p class="text-gray-700">50% refund available</p>
            </div>

            <div class="bg-[#b3bec8] p-8 rounded-2xl text-black transition-all hover:-translate-y-1 flex flex-col items-center md:items-start text-center md:text-left min-h-[140px]">
                <h3 class="text-xl font-bold mb-2">Secure Payments</h3>
                <p class="text-gray-700">Encrypted transactions</p>
            </div>

            <div class="bg-[#b3bec8] p-8 rounded-2xl text-black transition-all hover:-translate-y-1 flex flex-col items-center md:items-start text-center md:text-left min-h-[140px]">
                <h3 class="text-xl font-bold mb-2">Live Availability</h3>
                <p class="text-gray-700">Real-time seat updates</p>
            </div>

            <div class="bg-[#b3bec8] p-8 rounded-2xl text-black transition-all hover:-translate-y-1 flex flex-col items-center md:items-start text-center md:text-left min-h-[140px]">
                <h3 class="text-xl font-bold mb-2">User Dashboard</h3>
                <p class="text-gray-700">Manage bookings easily</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="text-center py-20 md:py-32 bg-[#F6F6F6] text-black">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-4xl md:text-6xl font-extrabold mb-6 leading-[1.2]">Ready to Experience Cinema?</h2>
        <p class="text-xl mb-10 text-gray-700">Join thousands of movie lovers today</p>
        <a href="{{ route('theatres.index') }}" class="inline-block px-5 py-4 md:px-10 md:py-5 rounded-xl font-bold no-underline relative cursor-pointer text-sm md:text-base transition-all whitespace-nowrap bg-gradient-to-r from-[#006989] to-[#6482AD] text-white hover:shadow-lg active:scale-95 transition-transform block w-full max-w-[280px] mx-auto md:inline-block">
            Start Booking Now →
        </a>
    </div>
</section>

<script>
    /* Slider Logic */
    document.addEventListener('DOMContentLoaded', function() {
        const viewport = document.querySelector('.slider-viewport');
        if (!viewport) return;

        const slides = viewport.querySelectorAll('.slider-slide');
        const dots = viewport.querySelectorAll('.pagination-dot');
        const prevBtn = document.getElementById('slider-prev-btn');
        const nextBtn = document.getElementById('slider-next-btn');
        let currentIndex = 0;
        let slideInterval;

        function updateSlider(index) {
            if (slides.length === 0) return;
            slides.forEach(s => s.classList.remove('is-active'));
            dots.forEach(d => d.classList.remove('is-active'));
            
            slides[index].classList.add('is-active');
            dots[index].classList.add('is-active');
            currentIndex = index;
        }

        function nextSlide() {
            if (slides.length === 0) return;
            let next = (currentIndex + 1) % slides.length;
            updateSlider(next);
        }

        function prevSlide() {
            if (slides.length === 0) return;
            let prev = (currentIndex - 1 + slides.length) % slides.length;
            updateSlider(prev);
        }

        if (nextBtn) nextBtn.addEventListener('click', () => { nextSlide(); resetInterval(); });
        if (prevBtn) prevBtn.addEventListener('click', () => { prevSlide(); resetInterval(); });

        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                updateSlider(i);
                resetInterval();
            });
        });

        function startInterval() {
            if (slides.length <= 1) return;
            slideInterval = setInterval(nextSlide, 5000);
        }

        function resetInterval() {
            clearInterval(slideInterval);
            startInterval();
        }

        startInterval();
    });
</script>
</x-movie-layout>
