@php
    $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
    $cols = 10;
    $ticketPrice = $showtime->ticket_price;
    $subtotal = count($selectedSeats) * $ticketPrice;
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-[#6482AD] rounded-2xl p-4 md:p-10 border border-white/10 relative shadow-sm overflow-hidden">
            <div class="flex flex-col items-center justify-center mb-16">
                <div class="w-3/5 h-4 bg-[#006989]/20 rounded-[50%] blur-xl -mb-2"></div>
                <div class="w-4/5 h-2 bg-slate-200 rounded-full shadow-sm border border-slate-300"></div>
                <p class="text-[10px] md:text-xs text-white/60 mt-6 tracking-[0.25em] font-bold uppercase text-center">Screen This Way</p>
            </div>

            <div class="flex flex-col items-center gap-2 md:gap-3 mb-12 overflow-x-auto pb-4">
                @foreach ($rows as $row)
                    <div class="flex items-center gap-2 md:gap-3" wire:key="row-{{ $row }}">
                        <div class="w-4 md:w-6 text-center text-[10px] md:text-xs font-bold text-white/80">{{ $row }}</div>
                        @for ($c = 1; $c <= $cols; $c++)
                            @php
                                $seatId = $row.$c;
                                $isBooked = in_array($seatId, $bookedSeats, true);
                                $isSelected = in_array($seatId, $selectedSeats, true);
                            @endphp
                            <button
                                type="button"
                                wire:key="seat-{{ $seatId }}"
                                wire:click="toggleSeat('{{ $seatId }}')"
                                @disabled($isBooked)
                                title="{{ $seatId }}"
                                class="w-6 h-6 md:w-8 md:h-8 rounded-t-lg border transition-all duration-300
                                    {{ $isBooked ? 'bg-slate-400 border-slate-500 opacity-90 cursor-not-allowed' : '' }}
                                    {{ ! $isBooked && ! $isSelected ? 'bg-white border-slate-300 hover:border-[#020617] cursor-pointer' : '' }}
                                    {{ $isSelected ? 'bg-[#020617] border-[#020617] text-white' : '' }}"
                            ></button>
                        @endfor
                    </div>
                @endforeach
            </div>

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

    <div class="lg:col-span-1">
        <div class="bg-[#6482AD] rounded-2xl p-8 border border-white/10 sticky top-24 shadow-sm text-white">
            <h3 class="text-xl font-bold mb-6">Booking Summary</h3>

            @error('seats')
                <p class="mb-4 text-sm font-semibold text-red-200">{{ $message }}</p>
            @enderror

            <div class="mb-8">
                <p class="text-[10px] font-bold text-white/60 uppercase tracking-wider mb-2">Seats</p>
                <div class="text-sm font-medium flex flex-wrap gap-2 min-h-[2rem]">
                    @forelse ($selectedSeats as $seat)
                        <span class="px-2 py-1 bg-white/20 rounded text-xs font-bold">{{ $seat }}</span>
                    @empty
                        <span class="text-white/40 italic">No seats selected</span>
                    @endforelse
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
                        <p class="text-sm font-semibold">LKR {{ number_format($ticketPrice, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/10 pt-6 mb-8">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-white/70 text-sm">Subtotal</span>
                    <span class="font-semibold">LKR {{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between items-center text-xl font-bold mt-6 pt-6 border-t border-white/10">
                    <span>Total</span>
                    <span>LKR {{ number_format($subtotal, 2) }}</span>
                </div>
            </div>

            <button
                type="button"
                wire:click="proceedToPayment"
                wire:loading.attr="disabled"
                @disabled(count($selectedSeats) === 0)
                class="w-full py-4 rounded-xl font-bold transition-all duration-300 border border-white/20 disabled:bg-white/10 disabled:text-white/30 disabled:cursor-not-allowed bg-[#020617] text-white cursor-pointer shadow-lg hover:bg-black hover:-translate-y-1"
            >
                <span wire:loading.remove wire:target="proceedToPayment">Proceed to Payment</span>
                <span wire:loading wire:target="proceedToPayment">Processing...</span>
            </button>
        </div>
    </div>
</div>
