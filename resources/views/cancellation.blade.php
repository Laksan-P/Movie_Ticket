<x-movie-layout>
    <section class="min-h-screen py-6 md:py-12 pb-28 px-4 bg-[#F6F6F6]">
        <div class="w-full mx-auto max-w-4xl relative z-10">
            <!-- Header -->
            @if(session('error'))
                <div class="mb-6 rounded-xl border border-red-300 bg-red-50 p-4 text-sm font-semibold text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-8 px-2 md:px-0">
                <a href="{{ route('bookings.index') }}"
                    class="text-[#020617] hover:text-[#0F4C75] transition-colors mb-4 inline-flex items-center gap-2 no-underline font-semibold">
                    ← Back to Bookings
                </a>
                <h1 class="text-3xl md:text-4xl font-bold mb-2 text-[#020617]">Cancel Booking</h1>
                <p class="text-slate-500">You can cancel your booking anytime with 50% refund</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Cancellation Form (Left) -->
                <div class="md:col-span-2 relative z-10">
                    <div
                        class="bg-[#6482AD] rounded-2xl p-6 md:p-8 border border-white/10 shadow-sm text-white overflow-hidden pointer-events-auto">
                        <!-- Movie Details -->
                        <div class="mb-8 pb-8 border-b border-white/10">
                            <h2 class="text-2xl md:text-4xl font-bold mb-4">
                                {{ data_get($booking, 'showtime.movie.title') }}
                            </h2>
                            <div class="space-y-3 text-white/70 font-medium text-sm">
                                <p><strong class="text-white">Theatre:</strong>
                                    {{ data_get($booking, 'showtime.theatre.name') }}</p>
                                <p><strong class="text-white">Date & Time:</strong>
                                    {{ data_get($booking, 'showtime.showtime') ? \Carbon\Carbon::parse(data_get($booking, 'showtime.showtime'))->format('M d, Y - h:i A') : 'N/A' }}
                                </p>
                                <p><strong class="text-white">Ticket Price:</strong> LKR
                                    {{ number_format(data_get($booking, 'showtime.ticket_price', 0), 2) }}
                                </p>
                                <p><strong class="text-white">Tickets:</strong>
                                    {{ data_get($booking, 'number_of_tickets') }}</p>
                                <p><strong class="text-white">Booking ID:</strong>
                                    #{{ str_pad(data_get($booking, 'id', 0), 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>

                        <!-- Important Notice -->
                        <div class="mb-8 p-6 bg-white/5 border-l-4 border-red-500 rounded-lg">
                            <h3 class="text-lg font-bold mb-4 text-white">Before You Cancel</h3>
                            <ul class="text-sm text-white/80 space-y-3 list-none p-0">
                                <li><span class="mr-2 text-red-500 font-bold">•</span> Your booking will be cancelled
                                    permanently</li>
                                <li><span class="mr-2 text-red-500 font-bold">•</span> You will receive 50% of your
                                    ticket price as refund</li>
                                <li><span class="mr-2 text-red-500 font-bold">•</span> Refund is processed immediately
                                </li>
                            </ul>
                        </div>

                        <div id="cancel-error" class="hidden mb-6 rounded-xl border border-red-300 bg-red-50 p-4 text-sm font-semibold text-red-700" role="alert"></div>

                        <!-- Cancellation Form -->
                        <form action="{{ route('bookings.cancel.confirm', data_get($booking, 'id')) }}" method="POST"
                            id="cancellation-form" class="space-y-6 relative z-10 pointer-events-auto">
                            {{-- Prevent Cross-Site Request Forgery using CSRF token validation --}}
                            @csrf
                            <div>
                                <label for="reason" class="block text-sm font-bold text-white mb-3">Reason for
                                    Cancellation</label>
                                <select id="reason" name="reason"
                                    class="w-full bg-[#D1D5DB] border border-[#9CA3AF] text-[#020617] p-3 md:p-4 rounded-lg focus:outline-none transition-all cursor-pointer font-medium"
                                    required>
                                    <option value="">Select a Reason...</option>
                                    <option value="Cannot attend">Cannot attend the show</option>
                                    <option value="Schedule conflict">Schedule conflict</option>
                                    <option value="Emergency">Emergency</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div>
                                <label for="comments" class="block text-sm font-bold text-white mb-3">Additional
                                    Comments (Optional)</label>
                                <textarea id="comments" name="comments"
                                    class="w-full bg-[#D1D5DB] border border-[#9CA3AF] text-[#020617] p-4 rounded-lg focus:outline-none transition-all resize-none placeholder:text-slate-600 font-medium"
                                    rows="4" placeholder="Tell us more..."></textarea>
                            </div>

                            <div class="flex items-start gap-4">
                                <input type="checkbox" id="accept_terms" name="accept_terms" required
                                    class="w-5 h-5 mt-0.5 accent-[#020617] cursor-pointer flex-shrink-0">
                                <div class="text-sm text-[#020617] font-bold leading-relaxed">
                                    I agree to the <x-cancellation-policy-link class="inline-block relative z-10" /> (50% refund).
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="flex flex-col md:flex-row gap-4 pt-4">
                                <a href="{{ route('bookings.index') }}"
                                    class="flex-1 py-4 px-6 rounded-xl border-2 border-[#1E3A8A] bg-[#f6f6f6]/30 text-white font-bold text-center no-underline transition-all hover:-translate-y-1">
                                    Keep Booking
                                </a>
                                <button type="submit"
                                    class="flex-1 py-4 px-6 rounded-xl bg-[#0F4C75] text-white font-bold cursor-pointer transition-all shadow-lg hover:bg-black hover:-translate-y-1">
                                    Confirm Cancellation
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="md:col-span-1">
                    <div
                        class="bg-[#6482AD] rounded-2xl p-6 border border-white/10 sticky top-24 shadow-sm text-white overflow-hidden">
                        <h3 class="text-xl font-bold mb-6">Refund Summary</h3>

                        <div class="mb-8 pb-8 border-b border-white/10 space-y-4 font-medium">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-white/60">Original Amount</span>
                                <span class="font-bold">LKR
                                    {{ number_format(data_get($booking, 'total_price', 0), 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-white/60">Fee (50%)</span>
                                <span class="font-bold text-red-400">-LKR
                                    {{ number_format(data_get($booking, 'total_price', 0) * 0.5, 2) }}</span>
                            </div>
                        </div>

                        <div class="bg-[#3E6591] rounded-2xl p-6 mb-8 text-center border border-white/10">
                            <p class="text-xs text-white/60 mb-2 font-bold uppercase tracking-wider">You will receive
                            </p>
                            <p class="text-3xl font-bold">LKR
                                {{ number_format(data_get($booking, 'total_price', 0) * 0.5, 2) }}
                            </p>
                        </div>

                        <div class="bg-[#D1D5DB] rounded-2xl p-6 border border-[#9CA3AF]">
                            <h4 class="text-xs font-bold text-[#020617] uppercase tracking-widest mb-4 text-center">
                                Refund Status</h4>
                            <p class="text-[10px] text-slate-700 leading-relaxed font-bold text-center">Processed
                                immediately to your original payment method.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Cancellation Success Modal (hidden + pointer-events-none so it does not block the form) -->
    <div id="cancel-success-modal"
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
            <h2 class="text-3xl font-black text-[#020617] mb-3 text-center">Cancelled!</h2>
            <p class="text-slate-500 text-center font-medium leading-relaxed">Your booking has been cancelled. 50%
                refund is being processed.</p>
            <div class="mt-8 flex gap-2">
                <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce"></div>
                <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce [animation-delay:-0.15s]"></div>
                <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce [animation-delay:-0.3s]"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('cancellation-form');
            const cancelError = document.getElementById('cancel-error');
            const submitBtn = form?.querySelector('button[type="submit"]');

            function showCancelError(message) {
                if (!cancelError) {
                    alert(message);
                    return;
                }
                cancelError.textContent = message;
                cancelError.classList.remove('hidden');
            }

            function clearCancelError() {
                cancelError?.classList.add('hidden');
            }

            function showCancelSuccessModal() {
                const modal = document.getElementById('cancel-success-modal');
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

            form?.addEventListener('submit', async function (e) {
                e.preventDefault();
                clearCancelError();

                if (!form.reportValidity()) {
                    return;
                }

                const bookingId = "{{ data_get($booking, 'id') }}";
                const payload = {
                    reason: form.querySelector('#reason')?.value ?? '',
                    comments: form.querySelector('#comments')?.value ?? '',
                };

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Processing...';
                }

                try {
                    const response = await fetch("{{ url('/api/bookings') }}/" + bookingId + "/cancel", {
                        method: 'POST',
                        credentials: 'include',
                        headers: {
                            {{-- Prevent Cross-Site Request Forgery using CSRF token validation on AJAX requests --}}
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify(payload),
                    });

                    let data = {};
                    try {
                        data = await response.json();
                    } catch (parseError) {
                        data = {};
                    }

                    if (response.ok) {
                        showCancelSuccessModal();
                        setTimeout(() => {
                            window.location.href = "{{ route('bookings.index') }}";
                        }, 2500);
                        return;
                    }

                    const message = data.message
                        || (data.errors ? Object.values(data.errors).flat().join(' ') : null)
                        || 'Cancellation failed. Please try again.';
                    showCancelError(message);
                } catch (error) {
                    console.error('Cancellation error:', error);
                    showCancelError('An unexpected error occurred. Please try again.');
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Confirm Cancellation';
                    }
                }
            });
        });
    </script>

    <x-cancellation-policy-modal />
</x-movie-layout>