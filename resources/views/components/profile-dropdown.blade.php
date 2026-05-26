@auth
<div class="relative profile-dropdown" data-profile-dropdown>
    <button type="button" data-profile-dropdown-toggle
        class="flex items-center gap-3 pl-2 pr-3 py-1.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 transition-all cursor-pointer">
        <x-profile-avatar :user="Auth::user()" size="sm" class="border-2 border-white/30" />
        <div class="hidden lg:block text-left">
            <p class="text-sm font-semibold text-white leading-tight">{{ Auth::user()->name }}</p>
            <p class="text-[10px] font-bold uppercase tracking-wide {{ Auth::user()->role === 'admin' ? 'text-amber-300' : 'text-[#8DBCC7]' }}">
                {{ Auth::user()->role === 'admin' ? 'Admin' : 'Customer' }}
            </p>
        </div>
        <svg class="w-4 h-4 text-white/70 hidden lg:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div data-profile-dropdown-menu
        class="hidden absolute right-0 mt-2 w-56 rounded-xl bg-white shadow-xl border border-slate-100 py-2 z-[200]">
        <div class="px-4 py-3 border-b border-slate-100">
            <p class="text-sm font-bold text-[#020617] truncate">{{ Auth::user()->name }}</p>
            <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
        </div>
        <a href="{{ route('profile.index') }}"
            class="block px-4 py-2.5 text-sm font-semibold text-[#020617] no-underline hover:bg-slate-50 transition-colors">
            Profile
        </a>
        <a href="{{ route('profile.security') }}"
            class="block px-4 py-2.5 text-sm font-semibold text-[#020617] no-underline hover:bg-slate-50 transition-colors">
            Security Settings
        </a>
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}"
                class="block px-4 py-2.5 text-sm font-semibold text-[#6482AD] no-underline hover:bg-slate-50 transition-colors">
                Admin Panel
            </a>
        @endif
        <a href="{{ route('bookings.index') }}"
            class="block px-4 py-2.5 text-sm font-semibold text-[#020617] no-underline hover:bg-slate-50 transition-colors">
            My Bookings
        </a>
        <div class="border-t border-slate-100 mt-1 pt-1">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="block w-full text-left px-4 py-2.5 text-sm font-semibold text-red-600 bg-transparent border-none cursor-pointer hover:bg-red-50 transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        if (window.__profileDropdownInit) return;
        window.__profileDropdownInit = true;
        document.addEventListener('click', function (e) {
            document.querySelectorAll('[data-profile-dropdown]').forEach(function (wrap) {
                var menu = wrap.querySelector('[data-profile-dropdown-menu]');
                var toggle = wrap.querySelector('[data-profile-dropdown-toggle]');
                if (!menu || !toggle) return;
                if (toggle.contains(e.target)) {
                    menu.classList.toggle('hidden');
                } else if (!wrap.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        });
    })();
</script>
@endauth
