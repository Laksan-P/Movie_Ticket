// Modal Logic
function openFormatModal() {
    const modal = document.getElementById('formatModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        document.getElementById('modalContent').classList.remove('scale-95');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('formatModal');
    modal.classList.add('opacity-0');
    document.getElementById('modalContent').classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Option Selection Logic
let selectedOptions = {
    lang: null,
    format: null
};

function selectOption(btn, type) {
    // Determine the container (parent of the clicked button)
    const container = btn.parentElement;

    // Check if clicking the already selected button
    if (btn.classList.contains('selected')) {
        btn.classList.remove('selected');
        selectedOptions[type] = null;
        return;
    }

    // Remove selected state from all buttons in this container
    const buttons = container.querySelectorAll('.modal-option-btn');
    buttons.forEach(b => b.classList.remove('selected'));

    // Add selected state to clicked button
    btn.classList.add('selected');

    // Update state
    selectedOptions[type] = btn.dataset.value;
}

function applyModalFilters() {
    // Sync modal selections with dropdowns
    document.getElementById('filter-lang').value = selectedOptions.lang || 'all';
    document.getElementById('filter-format').value = selectedOptions.format || 'all';

    // Apply filters and scroll
    filterShowtimes();
    closeModal();

    // Scroll to showtimes section if any filter is active
    if (selectedOptions.lang || selectedOptions.format) {
        const showtimesSection = document.getElementById('showtimes-section') || document.querySelector('.theatre-row');
        if (showtimesSection) {
            showtimesSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
}


// Filter Logic
function filterShowtimes() {
    const dateFilter = document.getElementById('filter-date').value;
    const langFilter = document.getElementById('filter-lang').value;
    const formatFilter = document.getElementById('filter-format').value;
    const priceFilter = document.getElementById('filter-price').value;
    const timeFilter = document.getElementById('filter-time').value;

    const showtimes = document.querySelectorAll('.showtime-btn');
    let hasVisible = false;

    showtimes.forEach(btn => {
        let visible = true;

        if (langFilter !== 'all' && btn.dataset.language !== langFilter) visible = false;
        if (formatFilter !== 'all' && btn.dataset.format !== formatFilter) visible = false;
        if (timeFilter !== 'all' && btn.dataset.time !== timeFilter) visible = false;

        // Price Range Logic
        if (priceFilter !== 'all') {
            const price = parseInt(btn.dataset.price);
            const [min, max] = priceFilter.split('-').map(Number);
            if (max) {
                if (price < min || price > max) visible = false;
            } else {
                // 600+
                if (price < 600) visible = false;
            }
        }

        // Date Filter Logic
        if (dateFilter !== 'all' && btn.dataset.date !== dateFilter) visible = false;

        if (visible) {
            btn.style.display = 'flex';
            hasVisible = true;
        } else {
            btn.style.display = 'none';
        }
    });

    // Handle Empty States for Theatres
    document.querySelectorAll('.theatre-row').forEach(row => {
        const visibleButtons = row.querySelectorAll('.showtime-btn[style="display: flex;"]');
        if (visibleButtons.length === 0 && row.querySelectorAll('.showtime-btn').length > 0) {
            row.style.display = 'none';
        } else {
            row.style.display = 'block';
        }
    });
}
