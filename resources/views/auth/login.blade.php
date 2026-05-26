<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduMonitor - Sign In</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen bg-[#dff3ff] text-slate-800 font-sans antialiased">

<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-4xl bg-white rounded-[40px] shadow-xl overflow-hidden grid md:grid-cols-2 min-h-[500px]">
        
        <div class="bg-sky-400 p-10 md:p-12 flex flex-col justify-between text-white relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-10">
                    <i data-lucide="graduation-cap" class="h-8 w-8 text-white"></i>
                    <span class="text-2xl font-bold">EduMonitor</span>
                </div>

                <div class="space-y-6">
                    <h2 class="text-3xl font-extrabold leading-tight">Welcome Back</h2>
                    <p class="text-sky-100 text-base">
                        Access your school's academic management dashboard with secure, role-based permissions.
                    </p>

                    <div class="grid grid-cols-2 gap-4 pt-4">
                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4">
                            <div class="text-2xl font-bold text-white">500+</div>
                            <div class="text-sky-100 text-xs">Teachers Verified</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4">
                            <div class="text-2xl font-bold text-white">100+</div>
                            <div class="text-sky-100 text-xs">Schools Connected</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-sky-200 text-sm relative z-10 mt-8">
                &copy; {{ date('Y') }} EduMonitor
            </div>

            <div class="absolute -top-10 -left-10 w-40 h-40 border-[10px] border-sky-300 rounded-full opacity-40"></div>
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-sky-300 rounded-full opacity-30"></div>
        </div>

        <div class="p-10 md:p-12 flex flex-col justify-center bg-white">
            <h2 class="text-2xl font-bold text-gray-800 mb-1">Sign in to your account</h2>
            <p class="text-sm text-gray-500 mb-8">Enter your credentials to access your dashboard</p>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
                    <i data-lucide="alert-circle" class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5"></i>
                    <ul class="text-red-700 text-sm list-none p-0 m-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all" 
                           placeholder="you@example.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all pr-12" 
                               placeholder="Enter your password">
                        
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 outline-none">
                            <i data-lucide="eye" id="passwordIcon" class="h-5 w-5"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-sky-400 hover:bg-sky-500 text-white font-semibold py-3 rounded-full shadow-md transition-colors outline-none mt-2">
                    Sign In
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Don't have an account? 
                <a href="/register" class="text-sky-500 hover:underline font-semibold">Register</a>
            </p>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const iconElement = document.getElementById('passwordIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            iconElement.setAttribute('data-lucide', 'eye-off');
        } else {
            passwordInput.type = 'password';
            iconElement.setAttribute('data-lucide', 'eye');
        }
        lucide.createIcons();
    }
    lucide.createIcons();
</script>
</body>
</html>