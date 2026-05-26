<x-movie-layout>
    <section class="min-h-screen py-12 px-4 bg-[#F6F6F6]">
        <div class="w-full mx-auto max-w-4xl px-4">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-[#020617] mb-1">Security Settings</h1>
                <p class="text-sm text-slate-500">Manage password, two-factor authentication, and account security</p>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-xl border border-green-300 bg-green-50 p-4 text-sm font-semibold text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('status'))
                <div class="mb-6 rounded-xl border border-blue-300 bg-blue-50 p-4 text-sm font-semibold text-blue-800">
                    @if (session('status') === \Laravel\Fortify\Fortify::TWO_FACTOR_AUTHENTICATION_CONFIRMED)
                        Two-factor authentication has been enabled successfully.
                    @elseif (session('status') === \Laravel\Fortify\Fortify::RECOVERY_CODES_GENERATED)
                        New recovery codes have been generated. Store them securely.
                    @else
                        {{ session('status') }}
                    @endif
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-300 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <x-profile-nav active="security" />

            {{-- Change Password --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 mb-6">
                <h2 class="text-xl font-bold text-[#020617] mb-2">Change Password</h2>
                <p class="text-sm text-slate-500 mb-6">Use a strong, unique password for your MovieBuff account.</p>

                <form method="POST" action="{{ route('user-password.update') }}" class="space-y-4 max-w-lg">
                    {{-- Prevent Cross-Site Request Forgery using CSRF token validation --}}
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="current_password" class="block text-sm font-bold text-[#020617] mb-2">Current Password</label>
                        <div class="relative">
                            <input type="password" id="current_password" name="current_password" required autocomplete="current-password"
                                class="w-full bg-[#F6F6F6] border border-slate-200 p-4 pr-12 rounded-xl outline-none focus:border-[#6482AD]">
                            <button type="button" onclick="togglePassword('current_password', this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#6482AD] transition-colors bg-transparent border-none p-0 cursor-pointer"
                                aria-label="Show current password">
                                <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-bold text-[#020617] mb-2">New Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required autocomplete="new-password"
                                class="w-full bg-[#F6F6F6] border border-slate-200 p-4 pr-12 rounded-xl outline-none focus:border-[#6482AD]">
                            <button type="button" onclick="togglePassword('password', this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#6482AD] transition-colors bg-transparent border-none p-0 cursor-pointer"
                                aria-label="Show new password">
                                <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-[#020617] mb-2">Confirm New Password</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                                class="w-full bg-[#F6F6F6] border border-slate-200 p-4 pr-12 rounded-xl outline-none focus:border-[#6482AD]">
                            <button type="button" onclick="togglePassword('password_confirmation', this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#6482AD] transition-colors bg-transparent border-none p-0 cursor-pointer"
                                aria-label="Show password confirmation">
                                <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button type="submit"
                        class="px-6 py-3 rounded-xl bg-[#6482AD] text-white font-bold cursor-pointer hover:bg-[#006989] transition-all">
                        Update Password
                    </button>
                </form>
            </div>

            {{-- Two-Factor Authentication --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 mb-6">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-[#020617] mb-2">Two-Factor Authentication</h2>
                        <p class="text-sm text-slate-500 max-w-xl">
                            Two-factor authentication adds an additional security layer. After login, you will enter a code from your authenticator app or a recovery code.
                        </p>
                    </div>
                    @if ($user->two_factor_confirmed_at)
                        <span class="px-4 py-2 rounded-full bg-green-100 text-green-800 text-xs font-bold uppercase">Enabled</span>
                    @elseif ($user->two_factor_secret)
                        <span class="px-4 py-2 rounded-full bg-amber-100 text-amber-800 text-xs font-bold uppercase">Setup Pending</span>
                    @else
                        <span class="px-4 py-2 rounded-full bg-slate-100 text-slate-600 text-xs font-bold uppercase">Disabled</span>
                    @endif
                </div>

                <p class="text-xs text-slate-500 mb-6 rounded-lg bg-slate-50 border border-slate-100 p-3">
                    Sensitive actions may require password confirmation.
                    <a href="{{ route('password.confirm') }}" class="text-[#6482AD] font-bold no-underline hover:underline">Confirm password</a> before enabling or disabling 2FA.
                </p>

                @if (! $user->two_factor_secret)
                    {{-- Enable 2FA --}}
                    <form method="POST" action="{{ route('two-factor.enable') }}">
                        @csrf
                        <button type="submit"
                            class="px-6 py-3 rounded-xl bg-[#0F4C75] text-white font-bold cursor-pointer hover:bg-black transition-all shadow-lg">
                            Enable Two-Factor Authentication
                        </button>
                    </form>
                @elseif (! $user->two_factor_confirmed_at)
                    {{-- QR setup + OTP confirm --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="rounded-xl bg-[#F6F6F6] p-6 border border-slate-100 flex flex-col items-center">
                            <p class="text-sm font-bold text-[#020617] mb-4">Scan QR Code</p>
                            <div class="bg-white p-4 rounded-xl shadow-inner">
                                {!! $user->twoFactorQrCodeSvg() !!}
                            </div>
                            <p class="text-xs text-slate-500 mt-4 text-center">Use Google Authenticator, Authy, or any TOTP app.</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-[#020617] mb-4">Enter Verification Code</p>
                            <form method="POST" action="{{ route('two-factor.confirm') }}" class="space-y-4">
                                @csrf
                                <input type="text" name="code" inputmode="numeric" autocomplete="one-time-code" required
                                    placeholder="6-digit code"
                                    class="w-full bg-[#F6F6F6] border border-slate-200 p-4 rounded-xl outline-none focus:border-[#6482AD] text-center text-lg tracking-widest font-mono">
                                <button type="submit"
                                    class="w-full px-6 py-3 rounded-xl bg-[#6482AD] text-white font-bold cursor-pointer hover:bg-[#006989] transition-all">
                                    Confirm &amp; Activate 2FA
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    {{-- 2FA enabled: recovery codes + disable --}}
                    @if (session('recoveryCodes'))
                        <div class="mb-6 rounded-xl border border-amber-300 bg-amber-50 p-6">
                            <p class="text-sm font-bold text-amber-900 mb-3">Store these recovery codes securely — each can only be used once:</p>
                            <div class="grid grid-cols-2 gap-2 font-mono text-sm">
                                @foreach (session('recoveryCodes') as $code)
                                    <code class="bg-white px-3 py-2 rounded border border-amber-200">{{ $code }}</code>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-wrap gap-4 mb-6">
                        <form method="POST" action="{{ route('two-factor.regenerate-recovery-codes') }}">
                            @csrf
                            <button type="submit"
                                class="px-6 py-3 rounded-xl border-2 border-[#6482AD] text-[#6482AD] font-bold cursor-pointer hover:bg-[#6482AD] hover:text-white transition-all">
                                Show / Regenerate Recovery Codes
                            </button>
                        </form>
                    </div>

                    <form method="POST" action="{{ route('two-factor.disable') }}"
                        onsubmit="return confirm('Disable two-factor authentication? Your account will be less secure.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-6 py-3 rounded-xl bg-red-600 text-white font-bold cursor-pointer hover:bg-red-700 transition-all">
                            Disable Two-Factor Authentication
                        </button>
                    </form>
                @endif
            </div>

            <div class="rounded-xl bg-[#6482AD]/10 border border-[#6482AD]/20 p-5">
                <p class="text-xs text-[#020617]/70 leading-relaxed">
                    <strong class="text-[#020617]">Login with 2FA:</strong> After entering email and password, you will be redirected to an OTP verification page. Use your authenticator app code or a recovery code to complete sign-in.
                </p>
            </div>
        </div>
    </section>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('.eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }
    </script>
</x-movie-layout>
