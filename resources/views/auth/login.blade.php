<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pustaka V</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#0B0F17] font-sans antialiased text-gray-200 min-h-screen flex items-center justify-center p-4 md:p-6">

    <!-- FLOATING CARD CONTAINER -->
    <div class="w-full max-w-5xl bg-[#111726] border border-gray-800/80 rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[620px]">
        
        <!-- BAGIAN KIRI: GRADIENT BACKGROUND & BRANDING -->
        <div class="relative w-full md:w-1/2 p-8 md:p-10 flex flex-col justify-between overflow-hidden bg-gradient-to-br from-[#1E1035] via-[#2A1B4E] to-[#0D3B4C]">
            
            <!-- Abstract Wave Background Overlay -->
            <div class="absolute inset-0 opacity-40 pointer-events-none">
                <div class="absolute -top-20 -left-20 w-80 h-80 bg-purple-600 rounded-full mix-blend-screen filter blur-3xl opacity-50"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-teal-500 rounded-full mix-blend-screen filter blur-3xl opacity-30"></div>
                <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <path d="M-100 200 C100 100, 200 300, 500 150 C700 50, 800 350, 1000 200" fill="none" stroke="rgba(255, 255, 255, 0.08)" stroke-width="80" />
                    <path d="M-100 350 C150 200, 300 450, 600 250" fill="none" stroke="rgba(168, 85, 247, 0.15)" stroke-width="40" />
                </svg>
            </div>

            <!-- Logo Brand Top Left -->
            <div class="flex items-center space-x-3 z-10">
                <div class="w-8 h-8 bg-[#FF5500] rounded-lg flex items-center justify-center font-bold text-white shadow-md">
                    P
                </div>
                <span class="text-white font-bold tracking-wide text-lg">Pustaka V</span>
            </div>

            <!-- Content Banner Center -->
            <div class="relative my-auto py-10 z-10">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight leading-tight">
                    Welcome Back!
                </h2>
                <p class="text-gray-300 text-xs md:text-sm mt-3 leading-relaxed max-w-sm">
                    Sign in to manage your active book loans, review reading history, and check active reserves.
                </p>
            </div>

            <!-- Footer Left Bottom -->
            <div class="text-[11px] text-gray-400/80 z-10">
                Official SMK Negeri 5 Surakarta Portal
            </div>
        </div>

        <!-- BAGIAN KANAN: FORM LOGIN -->
        <div class="w-full md:w-1/2 bg-[#111726] p-8 md:p-12 flex flex-col justify-center">
            <div class="max-w-md w-full mx-auto">
                
                <!-- Title Header -->
                <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-wide">Access Portal</h1>
                <p class="text-gray-400 text-xs mt-1 mb-8">Sign in with your registered student credentials</p>

                <!-- Form Login -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email / NIS Field -->
                    <div>
                        <label for="login" class="block text-xs font-medium text-gray-300 mb-1.5">Email / NIS</label>
                        <input 
                            type="text" 
                            id="login" 
                            name="login" 
                            value="{{ old('login') }}"
                            placeholder="E.g. student@student.sch.id or NIS" 
                            required 
                            autofocus
                            class="w-full px-4 py-3 bg-[#182032] border border-gray-700/60 rounded-xl text-white text-xs placeholder-gray-500 focus:outline-none focus:border-[#FF5500] focus:ring-1 focus:ring-[#FF5500] transition"
                        >
                        @error('email')
                            <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span>
                        @enderror
                        @error('login')
                            <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div x-data="{ showPassword: false }">
                        <label for="password" class="block text-xs font-medium text-gray-300 mb-1.5">Password</label>
                        <div class="relative">
                            <input 
                                :type="showPassword ? 'text' : 'password'" 
                                id="password" 
                                name="password" 
                                placeholder="••••••••" 
                                required 
                                class="w-full px-4 py-3 bg-[#182032] border border-gray-700/60 rounded-xl text-white text-xs placeholder-gray-500 focus:outline-none focus:border-[#FF5500] focus:ring-1 focus:ring-[#FF5500] transition pr-10"
                            >
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-white transition"
                            >
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.02 10.02 0 013.682-.782c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m-1.85 1.85L2 2l20 20" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="w-3.5 h-3.5 rounded bg-[#182032] border-gray-700 text-[#FF5500] focus:ring-0 transition accent-[#FF5500]"
                            >
                            <span class="text-xs text-gray-400">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#FF5500] hover:underline">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-3">
                        <button 
                            type="submit" 
                            class="w-full bg-[#FF5500] hover:bg-orange-600 text-white font-medium text-xs py-3 rounded-xl shadow-lg shadow-orange-500/20 transition duration-200"
                        >
                            Login
                        </button>
                    </div>
                </form>

                <!-- Footer Register Link -->
                <div class="text-center mt-6">
                    <p class="text-xs text-gray-400">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-[#FF5500] font-semibold hover:underline ml-0.5">
                            Register
                        </a>
                    </p>
                </div>

            </div>
        </div>

    </div>

</body>
</html>