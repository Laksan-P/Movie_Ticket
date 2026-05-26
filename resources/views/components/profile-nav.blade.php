@props(['active' => 'profile'])

<nav class="flex flex-wrap gap-2 mb-8">
    <a href="{{ route('profile.index') }}"
        class="px-5 py-2.5 rounded-xl text-sm font-bold no-underline transition-all {{ $active === 'profile' ? 'bg-[#6482AD] text-white shadow-md' : 'bg-white text-[#020617] border border-slate-200 hover:bg-slate-50' }}">
        Profile
    </a>
    <a href="{{ route('profile.edit') }}"
        class="px-5 py-2.5 rounded-xl text-sm font-bold no-underline transition-all {{ $active === 'edit' ? 'bg-[#6482AD] text-white shadow-md' : 'bg-white text-[#020617] border border-slate-200 hover:bg-slate-50' }}">
        Edit Profile
    </a>
    <a href="{{ route('profile.security') }}"
        class="px-5 py-2.5 rounded-xl text-sm font-bold no-underline transition-all {{ $active === 'security' ? 'bg-[#6482AD] text-white shadow-md' : 'bg-white text-[#020617] border border-slate-200 hover:bg-slate-50' }}">
        Security Settings
    </a>
</nav>
