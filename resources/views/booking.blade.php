<x-movie-layout>
@php
    $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
    $cols = 10;
@endphp

<section class="min-h-screen py-6 md:py-12 px-4 bg-[#F6F6F6]">
    <div class="w-full mx-auto max-w-6xl">
        <!-- Header -->
        <div class="mb-8 flex items-end justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#020617] mb-1">{{ $showtime->movie->title }}</h1>
                <p class="text-sm text-slate-500">
                    {{ $showtime->theatre->name }} | 
                    {{ \Carbon\Carbon::parse($showtime->showtime)->format('M d, h:i A') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Seat Selection -->
            <div class="lg:col-span-2">
                <div class="bg-[#6482AD] rounded-2xl p-4 md:p-10 border border-white/10 relative shadow-sm overflow-hidden">
                    <!-- Screen visual -->
                    <div class="flex flex-col items-center justify-center mb-16">
                        <div class="w-3/5 h-4 bg-[#006989]/20 rounded-[50%] blur-xl -mb-2"></div>
                        <div class="w-4/5 h-2 bg-slate-200 rounded-full shadow-sm [transform:perspective(400px)_rotateX(-10deg)] border border-slate-300"></div>
                        <p class="text-[10px] md:text-xs text-white/60 mt-6 tracking-[0.25em] font-bold uppercase text-center">Screen This Way</p>
                    </div>

                    <!-- Seat Grid -->
                    <div class="flex flex-col items-center gap-2 md:gap-3 mb-12 overflow-x-auto pb-4 scrollbar-hide">
                        @foreach($rows as $row)
                            <div class="flex items-center gap-2 md:gap-3">
                                <div class="w-4 md:w-6 text-center text-[10px] md:text-xs font-bold text-white/80">{{ $row }}</div>
                                @for($c=1; $c<=$cols; $c++)
                                    @php
                                        $seatId = "$row$c";
                                        $isBooked = in_array($seatId, $bookedSeats);
                                        $class = $isBooked ? 'bg-slate-400 border-slate-500 opacity-90 cursor-not-allowed' : 'bg-white border-slate-300 hover:border-[#020617] cursor-pointer';
                                    @endphp
                                    <button 
                                        type="button" 
                                        class="seat-btn w-6 h-6 md:w-8 md:h-8 rounded-t-lg border transition-all duration-300 relative [&.selected-seat]:!bg-[#020617] [&.selected-seat]:!border-[#020617] [&.selected-seat]:!text-white {{ $class }}" 
                                        data-seat="{{ $seatId }}"
                                        {{ $isBooked ? 'disabled' : "onclick=toggleSeat(this)" }}
                                        title="{{ $seatId }}"
                                    ></button>
                                @endfor
                            </div>
                        @endforeach
                    </div>

                    <!-- Legend -->
                    <div class="flex justify-center gap-6 md:gap-8 border-t border-white/10 pt-8 flex-wrap">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 rounded border border-white/30 bg-white"></div>
                            <span class="text-[10px] md:text-xs font-bold text-white">Available</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 rounded bg-[#020617]"></div>
                            <span class="text-[10px] md:text-xs font-bold text-white">Selected</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 rounded bg-slate-400 border border-slate-500 opacity-90"></div>
                            <span class="text-[10px] md:text-xs font-bold text-white">Booked</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Summary -->
            <div class="lg:col-span-1">
                <div class="bg-[#6482AD] rounded-2xl p-8 border border-white/10 sticky top-24 shadow-sm text-white">
                     <h3 class="text-xl font-bold mb-6">Booking Summary</h3>
                     
                     <div class="mb-8">
                         <div>
                             <p class="text-[10px] font-bold text-white/60 uppercase tracking-wider mb-2">Seats</p>
                             <div id="selected-seats-list" class="text-sm font-medium flex flex-wrap gap-2 min-h-[2rem]">
                                 <span class="text-white/40 italic">No seats selected</span>
                             </div>
                         </div>
                         <div class="grid grid-cols-2 gap-6 mt-6">
                            <div>
                                <p class="text-[10px] font-bold text-white/60 uppercase tracking-wider mb-1">Format</p>
                                <p class="text-sm font-semibold">{{ $showtime->format }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-white/60 uppercase tracking-wider mb-1">Language</p>
                                <p class="text-sm font-semibold">{{ $showtime->language }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-white/60 uppercase tracking-wider mb-1">Price/Ticket</p>
                                <p class="text-sm font-semibold">LKR {{ number_format($showtime->ticket_price, 2) }}</p>
                            </div>
                         </div>
                     </div>

                     <div class="border-t border-white/10 pt-6 mb-8">
                         <div class="flex justify-between items-center mb-3">
                             <span class="text-white/70 text-sm">Subtotal</span>
                             <span class="font-semibold" id="subtotal">LKR 0.00</span>
                         </div>
                         <div class="flex justify-between items-center text-xl font-bold mt-6 pt-6 border-t border-white/10">
                             <span>Total</span>
                             <span id="total-price">LKR 0.00</span>
                         </div>
                     </div>

                     <form action="{{ route('bookings.store') }}" method="POST">
                         @csrf
                         <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
                         <input type="hidden" name="number_of_tickets" id="input-tickets" value="0">
                         <input type="hidden" name="seats" id="input-seats" value="">
                         <input type="hidden" id="ticket-price" value="{{ $showtime->ticket_price }}">
                         
                         <button id="checkout-btn" type="submit" class="w-full py-4 rounded-xl font-bold transition-all duration-300 border border-white/20 disabled:bg-white/10 disabled:text-white/30 disabled:cursor-not-allowed bg-[#020617] text-white cursor-pointer shadow-lg hover:bg-black hover:-translate-y-1" disabled>
                             Proceed to Payment
                         </button>
                     </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    let selectedSeats = [];
    const ticketPrice = parseFloat(document.getElementById('ticket-price').value);

    function toggleSeat(btn) {
        const seatId = btn.getAttribute('data-seat');
        if (btn.classList.contains('selected-seat')) {
            btn.classList.remove('selected-seat');
            selectedSeats = selectedSeats.filter(s => s !== seatId);
        } else {
            btn.classList.add('selected-seat');
            selectedSeats.push(seatId);
        }
        updateSummary();
    }

    function updateSummary() {
        const list = document.getElementById('selected-seats-list');
        const inputSeats = document.getElementById('input-seats');
        const inputTickets = document.getElementById('input-tickets');
        const checkoutBtn = document.getElementById('checkout-btn');
        const subtotal = document.getElementById('subtotal');
        const total = document.getElementById('total-price');

        if (selectedSeats.length === 0) {
            list.innerHTML = '<span class="text-white/40 italic">No seats selected</span>';
            checkoutBtn.disabled = true;
        } else {
            list.innerHTML = selectedSeats.map(s => `<span class="px-2 py-1 bg-white/20 rounded text-xs font-bold">${s}</span>`).join('');
            checkoutBtn.disabled = false;
        }

        const cost = selectedSeats.length * ticketPrice;
        subtotal.innerText = `LKR ${cost.toLocaleString()}`;
        total.innerText = `LKR ${cost.toLocaleString()}`;
        inputSeats.value = selectedSeats.join(',');
        inputTickets.value = selectedSeats.length;
    }
</script>
</x-movie-layout>
