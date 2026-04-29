document.addEventListener('DOMContentLoaded', function () {
    // Selectors using new classes
    const slides = document.querySelectorAll('.slider-slide');
    const dots = document.querySelectorAll('.pagination-dot');
    const prevBtn = document.querySelector('#slider-prev-btn');
    const nextBtn = document.querySelector('#slider-next-btn');

    console.log('Slider Script Loaded');
    console.log('Slides found:', slides.length);
    console.log('Prev Button:', prevBtn);
    console.log('Next Button:', nextBtn);

    if (slides.length === 0) {
        console.warn('No slides found. Slider disabled.');
        return;
    }

    let currentIndex = 0;
    const intervalTime = 5000;
    let slideInterval;

    function showSlide(index) {
        // Wrap around
        if (index < 0) index = slides.length - 1;
        if (index >= slides.length) index = 0;

        currentIndex = index;

        // Update Slides
        slides.forEach((slide, i) => {
            if (i === currentIndex) {
                slide.classList.add('is-active');
            } else {
                slide.classList.remove('is-active');
            }
        });

        // Update Dots
        dots.forEach((dot, i) => {
            if (i === currentIndex) {
                dot.classList.add('is-active');
            } else {
                dot.classList.remove('is-active');
            }
        });
    }

    function nextSlide() {
        showSlide(currentIndex + 1);
    }

    function prevSlide() {
        showSlide(currentIndex - 1);
    }

    // Event Listeners
    if (nextBtn) nextBtn.addEventListener('click', () => {
        nextSlide();
        resetInterval();
    });

    if (prevBtn) prevBtn.addEventListener('click', () => {
        prevSlide();
        resetInterval();
    });

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            const index = parseInt(dot.getAttribute('data-index'));
            showSlide(index);
            resetInterval();
        });
    });

    // Auto Slide
    function startInterval() {
        slideInterval = setInterval(nextSlide, intervalTime);
    }

    function resetInterval() {
        clearInterval(slideInterval);
        startInterval();
    }

    startInterval();
});
