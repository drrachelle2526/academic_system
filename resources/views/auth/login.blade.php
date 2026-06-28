<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduMonitor - Sign In</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#dff3ff] text-slate-800 font-sans antialiased">

<div class="min-h-screen flex items-center justify-center p-4 md:p-6">
    <div class="w-full max-w-4xl bg-white rounded-[40px] shadow-xl overflow-hidden grid md:grid-cols-2 min-h-[550px]">
        
        <div class="p-10 md:p-12 flex flex-col justify-between bg-sky-50/60 border-r border-sky-100/80 relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-10">
                    <i data-lucide="graduation-cap" class="h-7 w-7 text-sky-500"></i>
                    <span class="text-2xl font-bold tracking-wide text-sky-950">EduMonitor</span>
                </div>

                <div class="space-y-6">
                    <h2 class="text-3xl font-extrabold leading-tight text-sky-950">Academic Performance Portal</h2>
                    <p class="text-slate-600 text-base leading-relaxed">
                        Access your school's secure management dashboard. Review administrative records, tracking frameworks, and role-based performance metrics.
                    </p>

                    <div class="grid grid-cols-2 gap-4 pt-4">
                        <div class="bg-white border border-sky-100 rounded-2xl p-4 shadow-sm">
                            <div class="text-2xl font-extrabold text-sky-600">500+</div>
                            <div class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Verified Teachers</div>
                        </div>
                        <div class="bg-white border border-sky-100 rounded-2xl p-4 shadow-sm">
                            <div class="text-2xl font-extrabold text-sky-600">100+</div>
                            <div class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Connected Schools</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-slate-400 text-xs font-medium relative z-10 mt-8">
                &copy; {{ date('Y') }} EduMonitor &bull; United Republic of Tanzania
            </div>

            <div class="absolute -top-10 -left-10 w-40 h-40 border-[10px] border-sky-200/40 rounded-full"></div>
        </div>

        <div class="p-10 md:p-12 flex flex-col justify-center bg-white">
            <h2 class="text-2xl font-extrabold text-sky-950 mb-1">Sign in to your account</h2>
            <p class="text-sm text-slate-500 mb-8">Enter your secure credentials below to authenticate access.</p>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-100 rounded-2xl p-4 flex items-start gap-3">
                    <i data-lucide="alert-circle" class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5"></i>
                    <ul class="text-red-700 text-sm list-none p-0 m-0 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-6 bg-amber-50 border border-amber-100 rounded-2xl p-4 flex items-start gap-3">
                    <i data-lucide="clock" class="h-5 w-5 text-amber-500 flex-shrink-0 mt-0.5"></i>
                    <p class="text-amber-800 text-sm font-medium">{{ session('status') }}</p>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Official Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all text-slate-800" 
                           placeholder="name@domain.go.tz">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Secure Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all pr-12 text-slate-800" 
                               placeholder="••••••••">
                        
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 outline-none">
                            <i data-lucide="eye" id="passwordIcon" class="h-5 w-5"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-sky-500 hover:bg-sky-600 text-white font-bold py-3 rounded-full shadow transition-colors outline-none mt-2">
                    Sign In to Portal
                </button>
            </form>

            <p class="text-center text-sm text-slate-500 mt-6">
                Need an account configuration? 
                <a href="/register" class="text-sky-500 hover:underline font-bold">Register here</a>
            </p>

            <div class="text-center mt-8 pt-4 border-t border-slate-100">
                <a href="/" class="inline-flex items-center gap-1.5 text-sm font-bold text-slate-500 hover:text-sky-500 transition-colors group focus:outline-none focus:underline">
                    <i data-lucide="arrow-left" class="h-4 w-4 transition-transform group-hover:-translate-x-1"></i>
                    Back to Homepage
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        lucide.createIcons();
    });

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
</script>
</body>
</html>
