<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'MovieBuff' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v={{ time() }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @livewireStyles

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'cinema-red': '#006989',
                        'cinema-gold': '#6482AD',
                    },
                    keyframes: {
                        fadeIn: {
                            'from': { opacity: '0', transform: 'translateY(20px)' },
                            'to': { opacity: '1', transform: 'translateY(0)' },
                        },
                        scaleUp: {
                            '0%': { transform: 'scale(0.8)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        }
                    },
                    animation: {
                        'fade-in-up': 'fadeIn 0.4s ease-out forwards',
                        'scale-up': 'scaleUp 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#F6F6F6] text-[#020617] min-h-screen flex flex-col">

<!-- HEADER -->
<header class="fixed top-0 left-0 w-full z-[100] backdrop-blur-xl bg-[#01161e]/90 border-b border-white/10 transition-all duration-300">
    <div class="max-w-[1440px] mx-auto px-6 h-20 flex justify-between items-center relative">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 no-underline z-20 group">
            <img src="{{ asset('assets/images/moviebuff-text-logo.png') }}" alt="MovieBuff Logo" class="h-[5.5rem] w-auto">
        </a>

        <!-- Desktop Nav -->
        <nav class="hidden md:flex items-center gap-8 absolute left-1/2 -translate-x-1/2 z-10">
            <a href="{{ route('home') }}" 
               class="text-sm font-medium transition-colors duration-200 no-underline {{ request()->routeIs('home') ? 'text-[#eaebed] font-bold' : 'text-gray-400 hover:text-[#8DBCC7]' }}">
                Home
            </a>
            <a href="{{ route('theatres.index') }}" 
               class="text-sm font-medium transition-colors duration-200 no-underline {{ request()->routeIs('theatres.*') ? 'text-[#eaebed] font-bold' : 'text-gray-400 hover:text-[#8DBCC7]' }}">
                Theatres
            </a>
        </nav>

        <!-- Auth Buttons -->
        <div class="hidden md:flex items-center gap-4 relative z-20">
            @auth
                <div class="flex items-center gap-4">
                    <span class="text-white font-medium">
                        Hello, <span class="text-[#f3e9dc]">{{ Auth::user()->name }}</span>
                    </span>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-[#c08552] font-semibold hover:text-[#f3e9dc] transition-colors">
                            Admin Panel
                        </a>
                    @endif
                    <a href="{{ route('bookings.index') }}" class="text-[#c08552] font-medium hover:text-[#f3e9dc] transition-colors no-underline">
                        My Bookings
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-transparent border-none cursor-pointer px-4 py-2 rounded-full border border-gray-600 text-sm font-medium text-gray-300 hover:text-white hover:border-gray-400 transition-all">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors no-underline">
                    Login
                </a>
                <a href="{{ route('register') }}" class="px-5 py-2 rounded-full bg-white/10 text-white text-sm font-semibold border border-white/5 backdrop-blur-sm hover:bg-white/20 transition-all no-underline">
                    Sign Up
                </a>
            @endauth
        </div>

        <!-- Mobile Menu Button -->
        <button id="mobile-menu-btn" class="md:hidden flex items-center justify-center w-10 h-10 text-white bg-white/10 rounded-lg border-none cursor-pointer hover:bg-white/20 transition-all z-[60]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div id="mobile-menu" class="fixed inset-0 z-[110] bg-[#01161e]/90 backdrop-blur-2xl hidden opacity-0 transition-opacity duration-400 p-6 pt-24 text-white">
    <button id="close-menu-btn" class="absolute top-6 right-4 text-white p-2 bg-transparent border-none cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <nav class="flex flex-col h-full">
        <a href="{{ route('home') }}" class="py-4 text-2xl font-bold no-underline {{ request()->routeIs('home') ? 'text-[#EDEDCE] relative after:content-[\'\'] after:absolute after:left-0 after:bottom-2 after:w-8 after:h-[2px] after:bg-[#EDEDCE]' : 'text-white' }}">Home</a>
        <a href="{{ route('theatres.index') }}" class="py-4 text-2xl font-bold no-underline {{ request()->routeIs('theatres.*') ? 'text-[#EDEDCE] relative after:content-[\'\'] after:absolute after:left-0 after:bottom-2 after:w-8 after:h-[2px] after:bg-[#EDEDCE]' : 'text-white' }}">Theatres</a>
        @auth
            <a href="{{ route('bookings.index') }}" class="py-4 text-2xl font-bold no-underline text-white">My Bookings</a>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full py-4 rounded-full bg-white/10 text-white font-bold border border-white/10 hover:bg-white hover:text-[#01161e] transition-all cursor-pointer">
                    Logout
                </button>
            </form>
        @else
            <div class="mt-auto mb-8 flex flex-col gap-4">
                <a href="{{ route('login') }}" class="w-full text-center py-4 rounded-full bg-white/10 text-white font-bold border border-white/10 no-underline">Login</a>
                <a href="{{ route('register') }}" class="w-full text-center py-4 rounded-full bg-gradient-to-r from-[#dc2626] to-[#fbbf24] text-white font-bold border-none no-underline">Sign Up</a>
            </div>
        @endauth
    </nav>
</div>

<div class="h-20"></div>

<main class="flex-grow">
    {{ $slot }}
</main>

<!-- FOOTER -->
<footer class="border-t border-white/10 bg-[#020617] mt-auto">
    <div class="max-w-[1500px] mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-[3fr_1fr_1fr] gap-12 mb-12">
            <!-- Brand -->
            <div class="col-span-1">
                <a href="{{ route('home') }}" class="inline-block">
                    <img src="{{ asset('assets/images/moviebuff-text-logo.png') }}" alt="MovieBuff" class="h-20 w-auto">
                </a>
                <p class="text-gray-400 text-sm leading-relaxed max-w-sm ml-2.5">
                    Premium movie booking experience. Find the best seats at the best theatres near you.
                </p>
            </div>

            <!-- Links -->
            <div class="pl-4 md:pl-0">
                <h4 class="text-white font-semibold mb-4 text-left">Quick Links</h4>
                <ul class="list-none p-0 m-0 flex flex-col gap-2">
                    <li><a href="{{ route('home') }}" class="text-sm text-gray-400 no-underline transition-colors hover:text-[#ffd700]">Home</a></li>
                    <li><a href="{{ route('theatres.index') }}" class="text-sm text-gray-400 no-underline transition-colors hover:text-[#ffd700]">Theatres</a></li>
                    @guest
                        <li><a href="{{ route('login') }}" class="text-sm text-gray-400 no-underline transition-colors hover:text-[#ffd700]">Login</a></li>
                    @endguest
                </ul>
            </div>

            <!-- Social -->
            <div class="pl-4 md:pl-0">
                <h4 class="text-white font-semibold mb-4 text-left">Connect</h4>
                <ul class="list-none p-0 m-0 flex flex-col gap-2">
                    <li><a href="#" class="text-sm text-gray-400 no-underline hover:text-[#ffd700]">Twitter</a></li>
                    <li><a href="#" class="text-sm text-gray-400 no-underline hover:text-[#ffd700]">Instagram</a></li>
                    <li><a href="#" class="text-sm text-gray-400 no-underline hover:text-[#ffd700]">Facebook</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/5 pt-8 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} MovieBuff. All rights reserved.
        </div>
    </div>
</footer>

<script>
    // Header Scroll Effect
    window.addEventListener('scroll', () => {
        const header = document.querySelector('header');
        if (header && window.scrollY > 50) {
            header.classList.add('py-2', 'bg-[#01161e]');
        } else if (header) {
            header.classList.remove('py-2', 'bg-[#01161e]');
        }
    });

    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const closeMenuBtn = document.getElementById('close-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
            setTimeout(() => {
                mobileMenu.classList.remove('opacity-0');
            }, 10);
        });
    }

    if (closeMenuBtn) {
        closeMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.add('opacity-0');
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
            }, 400);
        });
    }

</script>

@livewireScripts

</body>
</html>