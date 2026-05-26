<x-movie-layout>
    <section class="min-h-screen py-12 px-4 bg-[#F6F6F6]">
        <div class="w-full mx-auto max-w-4xl px-4">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-[#020617] mb-1">My Profile</h1>
                <p class="text-sm text-slate-500">View your account information and preferences</p>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-xl border border-green-300 bg-green-50 p-4 text-sm font-semibold text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <x-profile-nav active="profile" />

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-[#6482AD] to-[#006989] px-8 py-10 flex flex-col sm:flex-row items-center gap-6">
                    <x-profile-avatar :user="$user" size="lg" class="border-4 border-white shadow-lg" />
                    <div class="text-center sm:text-left text-white">
                        <h2 class="text-2xl font-bold mb-1">{{ $user->name }}</h2>
                        <p class="text-white/80 text-sm mb-3">{{ $user->email }}</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $user->role === 'admin' ? 'bg-amber-400 text-[#020617]' : 'bg-white/20 text-white' }}">
                            {{ $user->role === 'admin' ? 'Admin' : 'Customer' }}
                        </span>
                    </div>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="rounded-xl bg-[#F6F6F6] p-5 border border-slate-100">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Full Name</p>
                        <p class="text-[#020617] font-semibold">{{ $user->name }}</p>
                    </div>
                    <div class="rounded-xl bg-[#F6F6F6] p-5 border border-slate-100">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Email Address</p>
                        <p class="text-[#020617] font-semibold">{{ $user->email }}</p>
                    </div>
                    <div class="rounded-xl bg-[#F6F6F6] p-5 border border-slate-100">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Phone Number</p>
                        <p class="text-[#020617] font-semibold">{{ $user->phone ?: 'Not provided' }}</p>
                    </div>
                    <div class="rounded-xl bg-[#F6F6F6] p-5 border border-slate-100">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Account Role</p>
                        <p class="text-[#020617] font-semibold capitalize">{{ $user->role }}</p>
                    </div>
                    <div class="rounded-xl bg-[#F6F6F6] p-5 border border-slate-100 md:col-span-2">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Two-Factor Authentication</p>
                        <p class="text-[#020617] font-semibold">
                            @if ($user->two_factor_confirmed_at)
                                <span class="text-green-700">Enabled</span> — Extra login protection active
                            @else
                                <span class="text-amber-600">Not Enabled</span> — Recommended for account security
                            @endif
                        </p>
                    </div>
                </div>

                <div class="px-8 pb-8 flex flex-wrap gap-4">
                    <a href="{{ route('profile.edit') }}"
                        class="px-6 py-3 rounded-xl bg-[#6482AD] text-white font-bold no-underline hover:bg-[#006989] transition-all shadow-sm">
                        Edit Profile
                    </a>
                    <a href="{{ route('profile.security') }}"
                        class="px-6 py-3 rounded-xl border-2 border-[#6482AD] text-[#6482AD] font-bold no-underline hover:bg-[#6482AD] hover:text-white transition-all">
                        Security Settings
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-movie-layout>
