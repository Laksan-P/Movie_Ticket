<x-movie-layout>
<section class="min-h-screen flex items-center justify-center p-4 relative z-10 bg-[#F6F6F6] py-12 md:py-20">
    <div class="w-full max-w-[900px] bg-white rounded-3xl shadow-2xl flex flex-col md:flex-row overflow-hidden min-h-[720px]">
        <!-- Welcome Banner -->
        <div class="flex-1 bg-[#6482AD] p-8 md:p-12 flex flex-col justify-start md:pt-32 relative overflow-hidden text-white">
            <div class="relative z-10">
                <img src="{{ asset('assets/images/moviebuff-text-logo.png') }}" alt="MovieBuff Logo" class="w-[full] md:w-[180px] mb-6 md:mb-8 md:ml-3 shadow-sm mx-auto md:mx-0">
                <h2 id="banner-title" class="text-3xl md:text-[2.5rem] font-extrabold mb-4 ml-0 md:ml-3 leading-tight md:leading-none text-center md:text-left transition-all duration-700">Join MovieBuff!</h2>
                <p id="banner-text" class="text-base md:text-lg text-white/90 ml-0 md:ml-3 mb-8 md:mb-12 leading-relaxed text-center md:text-left transition-all duration-700">
                    Start your journey to premium cinema today.
                </p>
            </div>
            <div class="absolute -bottom-12 -right-12 w-[300px] h-[300px] bg-white/10 rounded-full blur-[40px]"></div>
        </div>

        <!-- Auth Content Container -->
        <div class="flex-1 flex flex-col bg-white relative">
            <!-- Toggle Switch (Fixed at Top) -->
            <div class="p-8 md:p-12 pb-0 flex justify-center z-20">
                <div class="bg-gray-100 p-1 rounded-full inline-flex relative w-64 h-11">
                    <div id="toggle-bg" class="absolute top-1 left-1 bottom-1 w-[calc(50%-4px)] bg-white rounded-full shadow-sm transition-all duration-500 cubic-bezier(0.4, 0, 0.2, 1) transform translate-x-[100%]"></div>
                    <button onclick="showAuth('login')" id="btn-login" class="flex-1 relative z-10 text-sm font-bold text-gray-400 transition-colors duration-500 border-none bg-transparent cursor-pointer">Login</button>
                    <button onclick="showAuth('register')" id="btn-register" class="flex-1 relative z-10 text-sm font-bold text-gray-800 transition-colors duration-500 border-none bg-transparent cursor-pointer">Register</button>
                </div>
            </div>

            <!-- Forms Slider Container -->
            <div class="flex-1 relative overflow-hidden">
                <div id="forms-slider" class="absolute inset-0 w-[200%] flex transition-transform duration-700 cubic-bezier(0.4, 0, 0.2, 1) transform translate-x-[-50%]">
                    
                    <!-- Login Form Section -->
                    <div class="w-[50%] flex-shrink-0 p-8 md:p-12 pt-4 flex flex-col justify-start">
                        <div class="text-center mb-8">
                            <h3 class="text-3xl font-extrabold text-gray-800 mb-2">Sign In</h3>
                            <p class="text-gray-500 text-sm">Enter your credentials to continue</p>
                        </div>

                        <form id="fortify-login-form" class="space-y-5" method="POST" action="{{ route('login.store') }}">
                            @csrf
                            <div>
                                <label for="login-email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                                <input type="email" id="login-email" name="email" required class="block w-full bg-gray-50 border border-gray-200 text-gray-800 px-4 py-3 rounded-xl text-sm focus:border-[#6482AD] focus:bg-white outline-none transition-all" placeholder="you@example.com">
                            </div>
                            <div>
                                <label for="login-password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                                <div class="relative">
                                    <input type="password" id="login-password" name="password" required class="block w-full bg-gray-50 border border-gray-200 text-gray-800 px-4 py-3 rounded-xl text-sm focus:border-[#6482AD] focus:bg-white outline-none transition-all pr-12" placeholder="••••••••">
                                    <button type="button" onclick="togglePassword('login-password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#6482AD] transition-colors bg-transparent border-none p-0 cursor-pointer">
                                        <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="remember" class="w-4 h-4 accent-[#6482AD]">
                                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                                </label>
                            </div>
                            <button type="submit" class="w-full py-3.5 bg-[#6482AD] hover:bg-[#00406c] text-white border-none rounded-xl font-bold text-lg cursor-pointer transition-all shadow-lg active:scale-95">
                                Sign In
                            </button>
                        </form>
                    </div>

                    <!-- Register Form Section -->
                    <div class="w-[50%] flex-shrink-0 p-8 md:p-12 pt-4 flex flex-col justify-start">
                        <div class="text-center mb-8">
                            <h3 class="text-3xl font-extrabold text-gray-800 mb-2">Create Account</h3>
                            <p class="text-gray-500 text-sm">Start your journey with us today</p>
                        </div>

                        <form class="space-y-4" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div>
                                <label for="reg-name" class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                                <input type="text" id="reg-name" name="name" required class="block w-full bg-gray-50 border border-gray-200 text-gray-800 px-4 py-2.5 rounded-xl text-sm focus:border-[#6482AD] focus:bg-white outline-none transition-all" placeholder="Enter full name" value="{{ old('name') }}">
                            </div>
                            <div>
                                <label for="reg-email" class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                                <input type="email" id="reg-email" name="email" required class="block w-full bg-gray-50 border border-gray-200 text-gray-800 px-4 py-2.5 rounded-xl text-sm focus:border-[#6482AD] focus:bg-white outline-none transition-all" placeholder="you@example.com" value="{{ old('email') }}">
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label for="reg-password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                                    <div class="relative">
                                        <input type="password" id="reg-password" name="password" required class="block w-full bg-gray-50 border border-gray-200 text-gray-800 px-4 py-2.5 rounded-xl text-sm focus:border-[#6482AD] focus:bg-white outline-none transition-all pr-12" placeholder="••••••••">
                                        <button type="button" onclick="togglePassword('reg-password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#6482AD] transition-colors bg-transparent border-none p-0 cursor-pointer">
                                            <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label for="reg-confirm" class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
                                    <div class="relative">
                                        <input type="password" id="reg-confirm" name="password_confirmation" required class="block w-full bg-gray-50 border border-gray-200 text-gray-800 px-4 py-2.5 rounded-xl text-sm focus:border-[#6482AD] focus:bg-white outline-none transition-all pr-12" placeholder="••••••••">
                                        <button type="button" onclick="togglePassword('reg-confirm', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#6482AD] transition-colors bg-transparent border-none p-0 cursor-pointer">
                                            <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="w-full mt-4 py-3.5 bg-[#6482AD] hover:bg-[#00406c] text-white border-none rounded-xl font-bold text-lg cursor-pointer transition-all shadow-lg active:scale-95">
                                Create Account
                            </button>
                        </form>
                    </div>

                </div>
            </div>

            <!-- Global Error Messages -->
            @if ($errors->any())
                <div class="mx-8 mb-8 bg-red-50 border border-red-100 rounded-lg p-4 z-20">
                    <ul class="text-red-600 text-sm list-none p-0 m-0 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</section>

<script>
    function showAuth(mode) {
        const slider = document.getElementById('forms-slider');
        const toggleBg = document.getElementById('toggle-bg');
        const btnLogin = document.getElementById('btn-login');
        const btnRegister = document.getElementById('btn-register');
        const bannerTitle = document.getElementById('banner-title');
        const bannerText = document.getElementById('banner-text');

        if (mode === 'register') {
            slider.style.transform = 'translateX(-50%)';
            toggleBg.style.transform = 'translateX(100%)';
            btnLogin.classList.replace('text-gray-800', 'text-gray-400');
            btnRegister.classList.replace('text-gray-400', 'text-gray-800');
            bannerTitle.innerText = 'Join MovieBuff!';
            bannerText.innerText = 'Start your journey to premium cinema today.';
        } else {
            slider.style.transform = 'translateX(0)';
            toggleBg.style.transform = 'translateX(0)';
            btnRegister.classList.replace('text-gray-800', 'text-gray-400');
            btnLogin.classList.replace('text-gray-400', 'text-gray-800');
            bannerTitle.innerText = 'Welcome Back!';
            bannerText.innerText = 'Your gateway to premium cinema experiences.';
        }
    }

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
