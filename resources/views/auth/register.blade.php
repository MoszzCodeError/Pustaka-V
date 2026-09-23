<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Pustaka V</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#0B0F19] text-gray-200 min-h-screen flex items-center justify-center p-0 lg:p-6">

    <div class="w-full max-w-6xl min-h-screen lg:min-h-[700px] bg-[#0B0F19] lg:bg-[#0D121F] lg:rounded-3xl lg:border lg:border-gray-800/80 overflow-hidden grid grid-cols-1 lg:grid-cols-2 shadow-2xl">
        
        <!-- BAGIAN KIRI: Visual Banner & Branding -->
        <div class="relative hidden lg:flex flex-col justify-between p-12 bg-cover bg-center border-r border-gray-800/60 overflow-hidden" 
             style="background-image: radial-gradient(circle at center, rgba(15, 23, 42, 0.4) 0%, rgba(11, 15, 25, 0.95) 100%), url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=1200&q=80');">
            
            <!-- Logo Header -->
            <div class="flex items-center space-x-3 z-10">
                <div class="w-10 h-10 bg-[#FF5500] rounded-2xl flex items-center justify-center font-bold text-white text-xl shadow-lg shadow-orange-500/30">P</div>
                <span class="text-xl font-bold tracking-wide text-white">Pustaka V</span>
            </div>

            <!-- Hero Text -->
            <div class="max-w-md z-10 my-auto">
                <h1 class="text-4xl font-extrabold text-white leading-tight tracking-tight mb-4">
                    Explore the World with Books
                </h1>
                <p class="text-gray-400 text-sm leading-relaxed font-normal">
                    Connect to SMKN 5 Surakarta's digital workspace. Access, borrow, learn, and grow anytime.
                </p>
            </div>

            <!-- Footer Text -->
            <div class="z-10 text-xs text-gray-500">
                Official SMK Negeri 5 Surakarta Portal
            </div>

            <!-- Overlay Glow Effects -->
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-orange-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        </div>

        <!-- BAGIAN KANAN: Form Register -->
        <div class="flex flex-col justify-center px-8 sm:px-14 py-10 bg-[#0B0F19]">
            
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-white tracking-wide">Create Account</h2>
                <p class="text-xs text-gray-400 mt-1">Start your premium library experience</p>
            </div>

            <!-- Pesan Error Validasi Utama -->
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-xs">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST" class="space-y-4" x-data="{ showPass: false, showConfirm: false }">
                @csrf

                <!-- Full Name -->
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="E.g. Bagus Saputra" required
                           class="w-full bg-[#111827] text-xs text-white px-4 py-3 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500] focus:ring-1 focus:ring-[#FF5500] transition placeholder-gray-600">
                </div>

                <!-- Email Address -->
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="bagus@student.sch.id" required
                           class="w-full bg-[#111827] text-xs text-white px-4 py-3 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500] focus:ring-1 focus:ring-[#FF5500] transition placeholder-gray-600">
                </div>

                <!-- NIS (Student ID) -->
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">NIS (Student ID)</label>
                    <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Enter 8-digit Student ID" required
                           class="w-full bg-[#111827] text-xs text-white px-4 py-3 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500] focus:ring-1 focus:ring-[#FF5500] transition placeholder-gray-600">
                </div>

                <!-- Class / Kelas -->
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">Class / Kelas</label>
                    <input type="text" name="class" value="{{ old('class') }}" placeholder="E.g. XII RPL 1" required
                           class="w-full bg-[#111827] text-xs text-white px-4 py-3 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500] focus:ring-1 focus:ring-[#FF5500] transition placeholder-gray-600">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">Password</label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" name="password" placeholder="••••••••" required
                               class="w-full bg-[#111827] text-xs text-white pl-4 pr-10 py-3 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500] focus:ring-1 focus:ring-[#FF5500] transition placeholder-gray-600">
                        <button type="button" @click="showPass = !showPass" class="absolute right-3 top-3 text-gray-500 hover:text-gray-300 transition focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1.5">Confirm Password</label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" placeholder="••••••••" required
                               class="w-full bg-[#111827] text-xs text-white pl-4 pr-10 py-3 rounded-xl border border-gray-800 focus:outline-none focus:border-[#FF5500] focus:ring-1 focus:ring-[#FF5500] transition placeholder-gray-600">
                        <button type="button" @click="showConfirm = !showConfirm" class="absolute right-3 top-3 text-gray-500 hover:text-gray-300 transition focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Terms & Conditions Checkbox -->
                <div class="flex items-center pt-1">
                    <input type="checkbox" id="terms" name="terms" required class="w-4 h-4 accent-[#FF5500] bg-[#111827] border-gray-800 rounded focus:ring-0 cursor-pointer">
                    <label for="terms" class="ml-2.5 text-[11px] text-gray-400 select-none">
                        I agree to Pustaka V's terms and SMKN 5 Surakarta library rules.
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 bg-[#FF5500] hover:bg-orange-600 text-white font-bold text-xs rounded-xl shadow-lg shadow-orange-500/20 transition duration-200 mt-2 cursor-pointer">
                    Sign Up
                </button>
            </form>


            <!-- Login Link -->
            <p class="text-center text-xs text-gray-500 mt-6">
                Already have an account? <a href="{{ route('login') }}" class="text-[#FF5500] font-semibold hover:underline">Login</a>
            </p>

        </div>

    </div>

</body>
</html>