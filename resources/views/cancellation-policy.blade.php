<x-movie-layout>
    <section class="min-h-screen py-12 px-4 bg-[#F6F6F6]">
        <div class="w-full mx-auto max-w-3xl">
            <!-- Header -->
            <div class="mb-12 text-center">
                <a href="{{ url()->previous() }}"
                    class="text-[#6482AD] hover:text-[#0F4C75] transition-colors mb-6 inline-flex items-center gap-2 no-underline font-bold">
                    ← Back to Previous Page
                </a>
                <h1 class="text-4xl md:text-5xl font-black text-[#020617] mb-4">Cancellation Policy</h1>
                <p class="text-slate-500 text-lg">Everything you need to know about refunds and cancellations</p>
            </div>

            <!-- Policy Content -->
            <div class="bg-white rounded-[2rem] p-8 md:p-12 shadow-sm border border-gray-100">
                <div class="space-y-10">
                    <!-- Section 1 -->
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <div
                                class="w-10 h-10 bg-[#6482AD]/10 rounded-full flex items-center justify-center text-[#6482AD]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-[#020617]">Cancellation Window</h2>
                        </div>
                        <p class="text-slate-600 leading-relaxed pl-14">
                            You can cancel your movie tickets anytime up to <strong>30 mins before</strong> the
                            scheduled showtime. Once the showtime has passed or is within the 30 mins window,
                            cancellations are no longer permitted.
                        </p>
                    </div>

                    <!-- Section 2 -->
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <div
                                class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-[#020617]">Refund Policy (50% Refund)</h2>
                        </div>
                        <p class="text-slate-600 leading-relaxed pl-14">
                            For all eligible cancellations, a <strong>50% refund</strong> of the total ticket price will
                            be processed. The remaining 50% is retained as a cancellation fee to cover administrative
                            costs and seat reservation losses.
                        </p>
                    </div>

                    <!-- Section 3 -->
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <div
                                class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-[#020617]">Processing Time</h2>
                        </div>
                        <p class="text-slate-600 leading-relaxed pl-14">
                            Refunds are initiated <strong>immediately</strong> upon successful cancellation. Depending
                            on your bank or payment provider, it may take 3-5 business days for the amount to reflect in
                            your original payment method.
                        </p>
                    </div>

                    <!-- Important Notice -->
                    <div class="mt-12 p-6 bg-[#020617] rounded-2xl text-white">
                        <h3 class="text-lg font-bold mb-2 flex items-center gap-2 text-red-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            Important Note
                        </h3>
                        <p class="text-white/80 text-sm leading-relaxed">
                            Once a cancellation is confirmed, the action cannot be undone. Your reserved seats will be
                            released immediately for other customers to book.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer Help -->
            <div class="mt-8 text-center text-slate-400 text-sm">
                <p>Have more questions? <a href="#" class="text-[#6482AD] font-bold">Contact Support</a></p>
            </div>
        </div>
    </section>
</x-movie-layout>