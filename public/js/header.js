document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const closeMenuBtn = document.getElementById('close-menu-btn');
    const mobileMenuLinks = document.querySelectorAll('.mobile-nav-link');
    const authActions = document.querySelector('.mobile-auth-actions');

    function toggleMenu() {
        if (!mobileMenu) return;

        const isHidden = mobileMenu.classList.contains('hidden');
        if (isHidden) {
            mobileMenu.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Trigger animation frame
            requestAnimationFrame(() => {
                mobileMenu.classList.remove('opacity-0');
                mobileMenu.classList.add('opacity-100');

                // Stagger animations
                mobileMenuLinks.forEach((link, index) => {
                    setTimeout(() => {
                        link.classList.add('show');
                    }, 50 + (index * 40));
                });

                if (authActions) {
                    setTimeout(() => {
                        authActions.classList.add('show');
                    }, 50 + (mobileMenuLinks.length * 40));
                }
            });
        } else {
            // Fade out
            mobileMenu.classList.remove('opacity-100');
            mobileMenu.classList.add('opacity-0');

            // Reset staggered items
            mobileMenuLinks.forEach(link => link.classList.remove('show'));
            if (authActions) authActions.classList.remove('show');

            setTimeout(() => {
                mobileMenu.classList.add('hidden');
            }, 500);
            document.body.style.overflow = '';
        }
    }

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', toggleMenu);
    }

    if (closeMenuBtn) {
        closeMenuBtn.addEventListener('click', toggleMenu);
    }

    mobileMenuLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (!mobileMenu.classList.contains('hidden')) {
                toggleMenu();
            }
        });
    });
});
