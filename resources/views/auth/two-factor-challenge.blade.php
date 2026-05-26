<x-movie-layout>
    <section class="min-h-screen flex items-center justify-center py-12 px-4 bg-[#F6F6F6]">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center bg-[#01161e] rounded-2xl px-8 py-5 mb-4 shadow-lg">
                    <img src="{{ asset('assets/images/moviebuff-text-logo.png') }}" alt="MovieBuff" class="h-14 w-auto">
                </div>
                <h1 class="text-2xl font-bold text-[#020617]">Two-Factor Verification</h1>
                <p class="text-sm text-slate-500 mt-2">Complete your secure login</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-lg p-8" x-data="{ recovery: false }">
                <div class="mb-4 text-sm text-slate-600" x-show="! recovery">
                    Enter the authentication code from your authenticator app.
                </div>
                <div class="mb-4 text-sm text-slate-600" x-cloak x-show="recovery">
                    Enter one of your emergency recovery codes.
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-300 bg-red-50 p-4 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('two-factor.login.store') }}">
                    {{-- Prevent Cross-Site Request Forgery using CSRF token validation --}}
                    @csrf

                    <div class="mb-4" x-show="! recovery">
                        <label for="code" class="block text-sm font-bold text-[#020617] mb-2">Authenticator Code</label>
                        <input id="code" type="text" inputmode="numeric" name="code" autofocus x-ref="code"
                            autocomplete="one-time-code" :required="! recovery"
                            class="w-full bg-[#F6F6F6] border border-slate-200 p-4 rounded-xl outline-none focus:border-[#6482AD] text-center text-lg tracking-widest font-mono">
                    </div>

                    <div class="mb-4" x-cloak x-show="recovery">
                        <label for="recovery_code" class="block text-sm font-bold text-[#020617] mb-2">Recovery Code</label>
                        <input id="recovery_code" type="text" name="recovery_code" x-ref="recovery_code"
                            autocomplete="one-time-code" :required="recovery"
                            class="w-full bg-[#F6F6F6] border border-slate-200 p-4 rounded-xl outline-none focus:border-[#6482AD] font-mono">
                    </div>

                    <div class="flex flex-col gap-3 mt-6">
                        <button type="submit"
                            class="w-full py-3 rounded-xl bg-[#6482AD] text-white font-bold cursor-pointer hover:bg-[#006989] transition-all">
                            Verify &amp; Continue
                        </button>
                        <button type="button" class="text-sm text-[#6482AD] font-semibold bg-transparent border-none cursor-pointer hover:underline"
                            x-show="! recovery"
                            x-on:click="recovery = true; $nextTick(() => { $refs.recovery_code.focus() })">
                            Use a recovery code instead
                        </button>
                        <button type="button" class="text-sm text-[#6482AD] font-semibold bg-transparent border-none cursor-pointer hover:underline"
                            x-cloak x-show="recovery"
                            x-on:click="recovery = false; $nextTick(() => { $refs.code.focus() })">
                            Use authenticator code instead
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</x-movie-layout>
