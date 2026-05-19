<x-movie-layout>
    <section class="min-h-screen py-12 pb-28 px-4 bg-[#F6F6F6]">
        <div class="w-full mx-auto max-w-4xl px-4 relative z-10">
            <!-- Header -->
            <div class="mb-8 items-end flex justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-[#020617] mb-1">Complete Your Payment</h1>
                    <p class="text-sm text-slate-500">Secure payment for your movie tickets</p>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-xl border border-green-300 bg-green-50 p-4 text-sm font-semibold text-green-800" role="status">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-6 rounded-xl border border-red-300 bg-red-50 p-4 text-sm font-semibold text-red-700" role="alert">{{ session('error') }}</div>
            @endif
            @if (session('info'))
                <div class="mb-6 rounded-xl border border-blue-300 bg-blue-50 p-4 text-sm font-semibold text-blue-800" role="status">{{ session('info') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Payment Form (Left) -->
                <div class="md:col-span-2 relative z-10 space-y-6">
                    @if (filled(config('services.stripe.key')) && filled(config('services.stripe.secret')) && data_get($booking, 'status') === 'pending')
                        <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm pointer-events-auto">
                            <h3 class="text-xl font-bold text-[#020617] mb-2">Pay with Stripe</h3>
                            <p class="text-sm text-slate-500 mb-6">Secure checkout via Stripe (test mode). Card details are handled by Stripe — not stored in this app.</p>
                            <form action="{{ route('bookings.payment.stripe', data_get($booking, 'id')) }}" method="POST" class="relative z-10 pointer-events-auto">
                                @csrf
                                <div class="flex items-start gap-3 mb-6">
                                    <input type="checkbox" id="accept_terms_stripe" name="accept_terms" value="1" required class="w-4 h-4 mt-1 accent-[#020617] cursor-pointer">
                                    <label for="accept_terms_stripe" class="text-sm text-slate-600 leading-relaxed font-medium cursor-pointer">
                                        I agree to the <x-cancellation-policy-link /> (50% refund).
                                    </label>
                                </div>
                                <button type="submit" class="w-full py-4 px-6 rounded-xl bg-[#635BFF] text-white font-bold cursor-pointer transition-all shadow-lg hover:bg-[#4f46e5]">
                                    Pay with Stripe
                                </button>
                            </form>
                        </div>
                    @endif
                    <div class="bg-[#6482AD] rounded-2xl p-8 border border-white/10 shadow-sm text-white pointer-events-auto">
                        <h3 class="text-xl font-bold mb-2">Demo Payment</h3>
                        <p class="text-sm text-white/70 mb-6">Mock card form for local testing (no real charges).</p>
                        <div id="payment-error" class="hidden mb-6 rounded-xl border border-red-300 bg-red-50 p-4 text-sm font-semibold text-red-700" role="alert"></div>
                        <form action="{{ route('bookings.confirm', data_get($booking, 'id')) }}" method="POST" id="payment-form" class="relative z-10 pointer-events-auto">
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
                                <p class="text-[11px] font-bold text-[#020617] mb-1 tracking-wide">🔒 MOCK PAYMENT</p>
                                <p class="text-[10px] text-[#020617]/50 leading-relaxed">Demo only: CVV is never stored. Only the card last four digits and payment metadata are saved.</p>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="flex items-start gap-3 mb-10">
                                <input type="checkbox" id="accept_terms" name="accept_terms" required
                                    class="w-4 h-4 mt-1 accent-[#020617] cursor-pointer">
                                <div class="text-sm text-[#020617]/70 leading-relaxed font-medium">
                                    I agree to the <x-cancellation-policy-link /> (50% refund).
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
                                    Demo Payment
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

    <!-- Payment Success Modal (hidden + pointer-events-none so it does not block the form) -->
    <div id="payment-success-modal"
        class="hidden pointer-events-none fixed inset-0 z-[200] min-h-screen items-center justify-center px-4 py-8 bg-[#020617]/80 backdrop-blur-md opacity-0 transition-opacity duration-500"
        aria-hidden="true">
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
            <p class="text-slate-500 text-center font-medium leading-relaxed">Your movie tickets are confirmed. Enjoy the show!</p>
            <a href="{{ route('bookings.index') }}"
                class="mt-8 w-full py-3 px-6 rounded-xl bg-[#0F4C75] text-white font-bold text-center no-underline transition-all hover:bg-black">
                Go to My Bookings
            </a>
            <p class="mt-3 text-xs text-slate-400 text-center">Redirecting automatically…</p>
            <div class="mt-6 flex gap-2" aria-hidden="true">
                <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce"></div>
                <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce [animation-delay:-0.15s]"></div>
                <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce [animation-delay:-0.3s]"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const paymentForm = document.getElementById('payment-form');
        const paymentError = document.getElementById('payment-error');
        const payButton = paymentForm?.querySelector('button[type="submit"]');

        function showPaymentError(message) {
            if (!paymentError) {
                alert(message);
                return;
            }
            paymentError.textContent = message;
            paymentError.classList.remove('hidden');
        }

        function clearPaymentError() {
            paymentError?.classList.add('hidden');
        }

        function showSuccessModal() {
            const modal = document.getElementById('payment-success-modal');
            const content = document.getElementById('modal-content');
            if (!modal) return;

            window.scrollTo(0, 0);

            modal.classList.remove('hidden', 'pointer-events-none');
            modal.classList.add('pointer-events-auto', 'flex');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => {
                modal.classList.add('opacity-100');
                content?.classList.remove('scale-90');
                content?.classList.add('scale-100');
            });
        }

        // Card Number Formatting (4-digit blocks)
        document.getElementById('card_number')?.addEventListener('input', function (e) {
            let input = e.target.value.replace(/\D/g, '');
            let formatted = '';
            for (let i = 0; i < input.length; i++) {
                if (i > 0 && i % 4 === 0) formatted += ' ';
                formatted += input[i];
            }
            e.target.value = formatted;
        });

        // Expiry Date Formatting (MM/YY) with Month Validation
        document.getElementById('card_expiry')?.addEventListener('input', function (e) {
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
        document.getElementById('cvv')?.addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });

        paymentForm?.addEventListener('submit', async function (e) {
            e.preventDefault();
            clearPaymentError();

            if (!paymentForm.reportValidity()) {
                return;
            }

            const bookingId = "{{ data_get($booking, 'id') }}";
            const paymentMethod = paymentForm.querySelector('input[name="payment_method"]:checked')?.value;
            const cardNumber = paymentForm.querySelector('#card_number')?.value ?? '';

            if (payButton) {
                payButton.disabled = true;
                payButton.textContent = 'Processing...';
            }

            try {
                const response = await fetch("{{ url('/api/bookings') }}/" + bookingId + "/confirm", {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        payment_method: paymentMethod,
                        card_number: cardNumber,
                    }),
                });

                let data = {};
                try {
                    data = await response.json();
                } catch (parseError) {
                    data = {};
                }

                if (response.ok) {
                    showSuccessModal();
                    setTimeout(() => {
                        window.location.href = "{{ route('bookings.index') }}";
                    }, 2500);
                    return;
                }

                const message = data.message
                    || (data.errors ? Object.values(data.errors).flat().join(' ') : null)
                    || 'Payment processing failed. Please try again.';

                showPaymentError(message);
            } catch (error) {
                console.error('Payment error:', error);
                showPaymentError('An unexpected error occurred. Please try again.');
            } finally {
                if (payButton) {
                    payButton.disabled = false;
                    payButton.textContent = 'Demo Payment';
                }
            }
        });
        });
    </script>

    <x-cancellation-policy-modal />
</x-movie-layout>