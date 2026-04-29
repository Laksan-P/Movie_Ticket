<x-movie-layout>
    <section class="min-h-screen py-12 px-4 bg-[#F6F6F6]">
        <div class="w-full mx-auto max-w-4xl px-4">
            <!-- Header -->
            <div class="mb-8 items-end flex justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-[#020617] mb-1">Complete Your Payment</h1>
                    <p class="text-sm text-slate-500">Secure payment for your movie tickets</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Payment Form (Left) -->
                <div class="md:col-span-2">
                    <div class="bg-[#6482AD] rounded-2xl p-8 border border-white/10 shadow-sm text-white">
                        <h3 class="text-xl font-bold mb-8">Payment Details</h3>
                        <form action="{{ route('bookings.confirm', data_get($booking, 'id')) }}" method="POST" id="payment-form">
                            @csrf

                            <!-- Payment Method -->
                            <div class="mb-8">
                                <label class="block text-sm font-bold text-[#020617] mb-4">Payment Method</label>
                                <div class="flex flex-col gap-3">
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="radio" name="payment_method" value="debit_card" checked
                                            class="w-4 h-4 accent-[#020617]">
                                        <span class="ml-3 font-semibold text-[#020617]">Debit Card</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="radio" name="payment_method" value="credit_card"
                                            class="w-4 h-4 accent-[#020617]">
                                        <span class="ml-3 font-semibold text-[#020617]">Credit Card</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Card Number -->
                            <div class="mb-6">
                                <label for="card_number" class="block text-sm font-bold text-[#020617] mb-2">Card
                                    Number</label>
                                <input type="text" id="card_number" name="card_number" required
                                    class="w-full bg-[#D1D5DB] border border-[#9CA3AF] text-[#020617] p-4 rounded-lg outline-none transition-all placeholder:text-slate-500"
                                    placeholder="1234 5678 9012 3456" maxlength="19">
                                <p class="text-[10px] text-[#020617] mt-2 font-medium italic px-1">Demo: 4532 1234 5678
                                    9010</p>
                            </div>

                            <!-- Card Holder -->
                            <div class="mb-6">
                                <label for="card_holder" class="block text-sm font-bold text-[#020617] mb-2">Cardholder
                                    Name</label>
                                <input type="text" id="card_holder" name="card_holder" required
                                    class="w-full bg-[#D1D5DB] border border-[#9CA3AF] text-[#020617] p-4 rounded-lg outline-none transition-all placeholder:text-slate-500"
                                    placeholder="Name on card">
                            </div>

                            <!-- Expiry & CVV -->
                            <div class="grid grid-cols-2 gap-6 mb-8">
                                <div>
                                    <label for="card_expiry" class="block text-sm font-bold text-[#020617] mb-2">Expiry
                                        Date</label>
                                    <input type="text" id="card_expiry" name="card_expiry" required
                                        class="w-full bg-[#D1D5DB] border border-[#9CA3AF] text-[#020617] p-4 rounded-lg outline-none placeholder:text-slate-500"
                                        placeholder="MM/YY" maxlength="5">
                                </div>
                                <div>
                                    <label for="cvv" class="block text-sm font-bold text-[#020617] mb-2">CVV</label>
                                    <input type="text" id="cvv" name="cvv" required
                                        class="w-full bg-[#D1D5DB] border border-[#9CA3AF] text-[#020617] p-4 rounded-lg outline-none placeholder:text-slate-500"
                                        placeholder="123" maxlength="3">
                                </div>
                            </div>

                            <!-- Security Info -->
                            <div class="bg-[#020617]/10 border border-[#020617]/20 rounded-xl p-4 mb-8">
                                <p class="text-[11px] font-bold text-[#020617] mb-1 tracking-wide">🔒 SECURE ENCRYPTION
                                </p>
                                <p class="text-[10px] text-[#020617]/50 leading-relaxed">Your payment information is
                                    encrypted and secure.</p>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="flex items-start gap-3 mb-10">
                                <input type="checkbox" id="accept_terms" name="accept_terms" required
                                    class="w-4 h-4 mt-1 accent-[#020617] cursor-pointer">
                                <div class="text-sm text-[#020617]/70 leading-relaxed font-medium">
                                    I agree to the <a href="{{ route('cancellation.policy') }}" target="_blank" onclick="event.stopPropagation()" class="text-[#020617] font-bold underline hover:text-blue-900 transition-colors pointer-events-auto">cancellation policy</a> (50% refund).
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-4 pt-4">
                                <a href="{{ route('theatres.index') }}"
                                    class="flex-1 py-4 px-6 rounded-xl border-2 border-[#1E3A8A] bg-[#f6f6f6]/30 text-white font-bold text-center no-underline transition-all">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="flex-1 py-4 px-6 rounded-xl bg-[#0F4C75] text-white font-bold cursor-pointer transition-all shadow-lg hover:bg-black">
                                    Pay
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div>
                    <div class="bg-[#6482AD] rounded-2xl p-8 border border-white/10 sticky top-24 shadow-sm text-white">
                        <h3 class="text-xl font-bold mb-6">Order Summary</h3>

                        <div class="space-y-4 mb-6 pb-6 border-b border-gray-800">
                            <div>
                                <p class="text-[10px] text-white/60 uppercase font-bold tracking-wider mb-2">Movie</p>
                                <p class="text-white font-semibold">{{ data_get($booking, 'showtime.movie.title') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-white/60 uppercase font-bold tracking-wider mb-2">Theatre</p>
                                <p class="text-white font-semibold">{{ data_get($booking, 'showtime.theatre.name') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-white/60 uppercase font-bold tracking-wider mb-2">Date & Time
                                </p>
                                <p class="text-white font-semibold">
                                    {{ data_get($booking, 'showtime.showtime') ? \Carbon\Carbon::parse(data_get($booking, 'showtime.showtime'))->format('M d, Y h:i A') : 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] text-white/60 uppercase font-bold tracking-wider mb-2">Price per Ticket</p>
                                <p class="text-white font-semibold">LKR {{ number_format(data_get($booking, 'showtime.ticket_price', 0), 2) }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-white/60 uppercase font-bold tracking-wider mb-2">Tickets</p>
                                <p class="text-white font-semibold">{{ data_get($booking, 'number_of_tickets', 0) }} Ticket(s)</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-white/60 uppercase font-bold tracking-wider mb-2">Seats</p>
                                <p class="text-white font-semibold">{{ data_get($booking, 'seats', 'N/A') }}</p>
                            </div>
                        </div>

                        <div class="bg-[#020617]/20 border border-white/10 rounded-xl p-6 mb-8">
                            <p class="text-[10px] text-white/60 mb-1 font-bold uppercase tracking-wider">Total Amount
                            </p>
                            <p class="text-2xl font-bold text-white">
                                LKR {{ number_format(data_get($booking, 'total_price', 0), 2) }}
                            </p>
                        </div>

                        <div
                            class="p-5 bg-[#020617]/20 border border-white/10 rounded-xl text-[10px] text-white/50 leading-relaxed">
                            <p class="font-bold text-white mb-2 uppercase">💡 50% REFUND POLICY</p>
                            <p>Cancel anytime and get 50% of your ticket price back.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Payment Success Modal -->
    <div id="payment-success-modal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-[#020617]/80 backdrop-blur-md hidden opacity-0 transition-opacity duration-500">
        <div class="bg-white rounded-[2rem] p-12 flex flex-col items-center max-w-sm w-full mx-4 shadow-[0_20px_50px_rgba(0,0,0,0.3)] transform scale-90 transition-transform duration-500"
            id="modal-content">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-8 relative">
                <div class="absolute inset-0 rounded-full bg-green-400 animate-ping opacity-20"></div>
                <svg class="w-12 h-12 text-green-600 relative z-10" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-black text-[#020617] mb-3 text-center">Payment Done!</h2>
            <p class="text-slate-500 text-center font-medium leading-relaxed">Your movie tickets are confirmed. Enjoy
                the show!</p>
            <div class="mt-8 flex gap-2">
                <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce"></div>
                <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce [animation-delay:-0.15s]"></div>
                <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce [animation-delay:-0.3s]"></div>
            </div>
        </div>
    </div>

    <script>
        // Card Number Formatting (4-digit blocks)
        document.getElementById('card_number').addEventListener('input', function (e) {
            let input = e.target.value.replace(/\D/g, '');
            let formatted = '';
            for (let i = 0; i < input.length; i++) {
                if (i > 0 && i % 4 === 0) formatted += ' ';
                formatted += input[i];
            }
            e.target.value = formatted;
        });

        // Expiry Date Formatting (MM/YY) with Month Validation
        document.getElementById('card_expiry').addEventListener('input', function (e) {
            let input = e.target.value.replace(/\D/g, '');

            // Auto-correct month if first digit is > 1
            if (input.length === 1 && input[0] > '1') {
                input = '0' + input;
            }

            // Cap month at 12
            if (input.length >= 2) {
                let month = parseInt(input.substring(0, 2));
                if (month > 12) input = '12' + input.substring(2);
                if (month === 0 && input.length >= 2) input = '01' + input.substring(2);
            }

            if (input.length > 2) {
                input = input.substring(0, 2) + '/' + input.substring(2, 4);
            }
            e.target.value = input;
        });

        // CVV - Numbers only
        document.getElementById('cvv').addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });

        // Payment Success Modal Handling
        document.getElementById('payment-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const modal = document.getElementById('payment-success-modal');
            const content = document.getElementById('modal-content');

            // Show modal with animation
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                content.classList.add('scale-100');
            }, 10);

            // Wait for 2.5 seconds then submit
            setTimeout(() => {
                this.submit();
            }, 2500);
        });
    </script>
</x-movie-layout>