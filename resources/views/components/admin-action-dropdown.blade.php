@props(['align' => 'right'])

<div class="relative inline-block text-left admin-action-dropdown" data-admin-dropdown>
    <button
        type="button"
        class="p-2 rounded-md text-[#020617]/70 hover:bg-black/10 hover:text-[#020617] transition-colors border-none cursor-pointer bg-transparent"
        data-admin-dropdown-toggle
        aria-haspopup="true"
        aria-expanded="false"
        aria-label="Row actions"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 4a2 2 0 110-4 2 2 0 010 4zm0 4a2 2 0 110-4 2 2 0 010 4z" />
        </svg>
    </button>

    <div
        class="hidden absolute z-30 mt-2 min-w-[10rem] rounded-lg bg-white border border-gray-200 shadow-lg py-1 {{ $align === 'left' ? 'left-0' : 'right-0' }}"
        data-admin-dropdown-menu
        role="menu"
    >
        {{ $slot }}
    </div>
</div>

@once
<script>
    (function () {
        if (window.__adminDropdownInit) return;
        window.__adminDropdownInit = true;

        function closeAllAdminDropdowns() {
            document.querySelectorAll('[data-admin-dropdown]').forEach(function (dropdown) {
                const menu = dropdown.querySelector('[data-admin-dropdown-menu]');
                const toggle = dropdown.querySelector('[data-admin-dropdown-toggle]');
                if (menu) menu.classList.add('hidden');
                if (toggle) toggle.setAttribute('aria-expanded', 'false');
            });
        }

        document.addEventListener('click', function (e) {
            const toggle = e.target.closest('[data-admin-dropdown-toggle]');
            if (toggle) {
                e.preventDefault();
                e.stopPropagation();
                const dropdown = toggle.closest('[data-admin-dropdown]');
                const menu = dropdown.querySelector('[data-admin-dropdown-menu]');
                const isOpen = !menu.classList.contains('hidden');
                closeAllAdminDropdowns();
                if (!isOpen) {
                    menu.classList.remove('hidden');
                    toggle.setAttribute('aria-expanded', 'true');
                }
                return;
            }

            if (!e.target.closest('[data-admin-dropdown]')) {
                closeAllAdminDropdowns();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeAllAdminDropdowns();
        });
    })();
</script>
@endonce
