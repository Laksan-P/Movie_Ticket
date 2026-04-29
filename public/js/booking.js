const maxSeats = 10;
const selectedSeats = new Set();
const checkoutBtn = document.getElementById('checkout-btn');
const inputTickets = document.getElementById('input-tickets');
const selectedList = document.getElementById('selected-seats-list');
const totalPriceEl = document.getElementById('total-price');
const subtotalEl = document.getElementById('subtotal');
// Read price from the hidden input we added
const pricePerTicket = parseFloat(document.getElementById('ticket-price').value);

function toggleSeat(btn) {
    const seatId = btn.getAttribute('data-seat');

    if (selectedSeats.has(seatId)) {
        selectedSeats.delete(seatId);
        btn.classList.remove('selected-seat');
    } else {
        if (selectedSeats.size >= maxSeats) {
            alert('You can only select up to ' + maxSeats + ' seats.');
            return;
        }
        selectedSeats.add(seatId);
        btn.classList.add('selected-seat');
    }

    updateSummary();
}

function updateSummary() {
    // Update List
    selectedList.innerHTML = '';
    if (selectedSeats.size === 0) {
        selectedList.innerHTML = '<span class="text-slate-400 italic">No seats selected</span>';

        // Disable button
        checkoutBtn.disabled = true;
        checkoutBtn.classList.remove('bg-[#006989]', 'shadow-[#006989]/20', 'shadow-lg');
        checkoutBtn.classList.add('bg-slate-100', 'text-slate-400', 'cursor-not-allowed');
    } else {
        // Only text comma separated
        selectedList.innerHTML = Array.from(selectedSeats).sort().join(', ');

        // Enable button
        checkoutBtn.disabled = false;
        checkoutBtn.classList.remove('bg-slate-100', 'text-slate-400', 'cursor-not-allowed');
        checkoutBtn.classList.add('bg-[#006989]', 'shadow-[#006989]/20', 'shadow-lg');
    }

    // Update Prices
    const count = selectedSeats.size;
    const total = count * pricePerTicket;

    subtotalEl.textContent = 'LKR ' + total.toLocaleString('en-IN', { minimumFractionDigits: 2 });
    totalPriceEl.textContent = 'LKR ' + total.toLocaleString('en-IN', { minimumFractionDigits: 2 });

    // Update Input
    inputTickets.value = count;
    document.getElementById('input-seats').value = Array.from(selectedSeats).join(',');
}
