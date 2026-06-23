<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Monitoring System - EduMonitor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#dff3ff] text-slate-800 font-sans antialiased scroll-smooth">

    <nav class="w-full py-6">
        <div class="max-w-7xl mx-auto bg-white rounded-full px-8 py-4 flex justify-between items-center shadow-lg">
            <div class="flex items-center gap-2">
                <i data-lucide="graduation-cap" class="h-8 w-8 text-sky-500"></i>
                <h1 class="text-2xl font-bold text-sky-950">EduMonitor</h1>
            </div>

            <ul class="hidden md:flex gap-8 text-slate-600 font-semibold">
                <li><a href="#" class="hover:text-sky-500 transition-colors">Home</a></li>
                <li><a href="#about" class="hover:text-sky-500 transition-colors">About</a></li>
                <li><a href="#features" class="hover:text-sky-500 transition-colors">Overview</a></li>
                <li><a href="#contact" class="hover:text-sky-500 transition-colors">Contact</a></li>
            </ul>

            <div class="flex items-center gap-6">
                <a href="/login" class="text-sky-600 hover:text-sky-700 font-bold transition-colors">
                    Login
                </a>
                <a href="/register"
                   class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-2.5 rounded-full shadow font-bold transition-all transform hover:-translate-y-0.5">
                    Register
                </a>
            </div>
        </div>
    </nav>

    <section class="max-w-7xl mx-auto px-6 py-6">
        <div class="bg-sky-500 rounded-[40px] p-10 md:p-16 grid md:grid-cols-2 items-center gap-10 relative overflow-hidden shadow-xl">
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 bg-white/20 border border-white/30 rounded-full px-4 py-1.5 mb-6">
                    <i data-lucide="shield" class="h-4 w-4 text-white"></i>
                    <span class="text-white text-xs font-bold tracking-wide uppercase">Verified Access Infrastructure</span>
                </div>

                <h2 class="text-4xl md:text-5xl font-extrabold text-white leading-tight">
                    Web-Based Academic & Performance Monitoring System
                </h2>

                <p class="mt-6 text-sky-100 text-lg leading-relaxed">
                    Empowering schools to manage academic performance, records, and attendance dashboards seamlessly with secure role verification.
                </p>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="#features"
                       class="bg-white text-sky-600 px-7 py-3.5 rounded-full font-bold shadow-md hover:bg-sky-50 transition-colors text-center">
                        Explore System
                    </a>
                    <a href="#features"
                       class="bg-sky-600/40 text-white border border-sky-300/30 px-7 py-3.5 rounded-full font-bold hover:bg-sky-600/60 transition-colors text-center">
                        Learn More
                    </a>
                </div>
            </div>

            <div class="relative z-10">
                <img
                    src="{{ asset('images/first_pic.png') }}"
                    alt="People illustrations by Storyset"
                    class="rounded-3xl shadow-2xl w-full max-h-[380px] object-cover border-4 border-white/20"
                >
            </div>

            <div class="absolute top-10 right-20 w-72 h-72 border-[20px] border-sky-400 rounded-full opacity-40"></div>
            <div class="absolute bottom-0 left-1/2 w-40 h-40 bg-sky-400 rounded-full opacity-30"></div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-6">
        <div class="bg-white rounded-[30px] p-8 shadow-md border border-sky-100">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 divide-y md:divide-y-0 md:divide-x divide-slate-100 text-center">
                <div class="pt-4 md:pt-0">
                    <h3 class="text-4xl font-black text-sky-500">100+</h3>
                    <p class="text-slate-500 font-semibold mt-2">Primary Schools Consolidated</p>
                </div>
                <div class="pt-4 md:pt-0">
                    <h3 class="text-4xl font-black text-sky-500">20,000+</h3>
                    <p class="text-slate-500 font-semibold mt-2">Active Students Managed</p>
                </div>
                <div class="pt-4 md:pt-0">
                    <h3 class="text-4xl font-black text-sky-500">500+</h3>
                    <p class="text-slate-500 font-semibold mt-2">Verified Teachers Connected</p>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="max-w-7xl mx-auto px-6 py-10">
        <div class="bg-sky-500 rounded-[40px] p-10 md:p-16 grid md:grid-cols-2 items-center gap-10 relative overflow-hidden shadow-xl">
            
            <div class="relative z-10">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4 leading-tight">
                    Hierarchical Verification System
                </h2>
                <p class="text-sky-100 mb-8 text-base leading-relaxed">
                    EduMonitor maintains direct structural validation chains down from higher administrators to local institutional levels to safeguard metric parameters.
                </p>
                
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0 border border-white/30">
                            <i data-lucide="shield" class="h-5 w-5 text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-base">System Administrator</h4>
                            <p class="text-sm text-sky-100/90">Maintains systemic parameters and authenticates incoming Ward Education Officers (WEO).</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0 border border-white/30">
                            <i data-lucide="users" class="h-5 w-5 text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-base">Ward Education Officer (WEO)</h4>
                            <p class="text-sm text-sky-100/90">Monitors performance indicators and verifies designated Head Teachers inside their ward.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0 border border-white/30">
                            <i data-lucide="user-check" class="h-5 w-5 text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-base">Head Teacher</h4>
                            <p class="text-sm text-sky-100/90">Manages high-level institutional summaries and activates structural school staff accounts.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0 border border-white/30">
                            <i data-lucide="file-text" class="h-5 w-5 text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-base">Class / Academic Teacher</h4>
                            <p class="text-sm text-sky-100/90">Inputs class grades, marks operational attendance, and organizes raw performance records.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative z-10 flex justify-center items-center">
                <img 
                    src="{{ asset('images/second_pic.png') }}" 
                    alt="Verification Setup Chain Illustration" 
                    class="rounded-3xl shadow-2xl w-full max-w-md border-4 border-white/20 object-cover max-h-[380px]"
                />
            </div>

            <div class="absolute top-10 right-20 w-72 h-72 border-[20px] border-sky-400 rounded-full opacity-40"></div>
            <div class="absolute bottom-0 left-1/2 w-40 h-40 bg-sky-400 rounded-full opacity-30"></div>
        </div>
    </section>

    <section id="features" class="max-w-7xl mx-auto px-6 py-10">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">Powerful Core Features</h2>
            <p class="text-slate-500 mt-2 font-medium">Everything needed to oversee administrative primary sector statistics.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-8 rounded-[30px] shadow-sm border border-sky-100 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-sky-100 rounded-2xl flex items-center justify-center mb-5">
                    <i data-lucide="folder-open" class="h-6 w-6 text-sky-600"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Student Records</h3>
                <p class="mt-3 text-slate-600 text-sm leading-relaxed">
                    Organize registration baselines and structural student demographics digitally inside searchable profile directories.
                </p>
            </div>

            <div class="bg-white p-8 rounded-[30px] shadow-sm border border-sky-100 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center mb-5">
                    <i data-lucide="trending-up" class="h-6 w-6 text-emerald-600"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Performance Tracking</h3>
                <p class="mt-3 text-slate-600 text-sm leading-relaxed">
                    Track academic trajectories across structural terms through secure marks logging frameworks.
                </p>
            </div>

            <div class="bg-white p-8 rounded-[30px] shadow-sm border border-sky-100 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center mb-5">
                    <i data-lucide="graduation-cap" class="h-6 w-6 text-amber-600"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Teacher Management</h3>
                <p class="mt-3 text-slate-600 text-sm leading-relaxed">
                    Authorize personnel verification workflows smoothly aligned to localized national assignment metrics.
                </p>
            </div>

            <div class="bg-white p-8 rounded-[30px] shadow-sm border border-sky-100 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center mb-5">
                    <i data-lucide="bar-chart-3" class="h-6 w-6 text-purple-600"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Instant Reports</h3>
                <p class="mt-3 text-slate-600 text-sm leading-relaxed">
                    Generate comprehensive institution evaluation summaries automatically using dynamic template frameworks.
                </p>
            </div>
        </div>
    </section>

   <section id="contact" class="max-w-7xl mx-auto px-6 pb-12 pt-6">
        <div class="bg-sky-500 rounded-[40px] p-10 md:p-14 relative overflow-hidden shadow-2xl">
            <div class="grid gap-8 lg:grid-cols-[1.5fr_1fr] items-center">
                <div class="relative z-10 text-white">
                    <h2 class="text-3xl md:text-4xl font-extrabold mb-4 leading-tight">
                        Ready to Transform Your School Configuration?
                    </h2>
                    <p class="text-sky-100 mb-8 max-w-2xl text-base font-medium">
                        Join regional academic departments optimizing performance management workflows with EduMonitor framework security configurations.
                    </p>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-3xl bg-white/10 border border-white/20 p-5">
                            <p class="text-sky-100 text-sm uppercase tracking-[.2em] mb-2">Contact Email</p>
                            <p class="text-white font-semibold">support@edumonitor.tz</p>
                        </div>
                        <div class="rounded-3xl bg-white/10 border border-white/20 p-5">
                            <p class="text-sky-100 text-sm uppercase tracking-[.2em] mb-2">Phone</p>
                            <p class="text-white font-semibold">+255 123 456 789</p>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 bg-white/10 border border-white/20 rounded-[30px] p-8 text-white shadow-lg">
                    <h3 class="text-xl font-bold mb-4">Contact the Education Office</h3>
                    <p class="text-sky-100 leading-relaxed mb-6">
                        For implementation support, registration questions, or project onboarding, contact us using the email or phone number shown above and we will connect you with the right team.
                    </p>
                    <a href="/register"
                       class="inline-block bg-white text-sky-600 font-bold px-8 py-3.5 rounded-full transition-all hover:bg-sky-50 shadow-lg">
                        Start Registration
                    </a>
                </div>
            </div>

            <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-sky-400 rounded-full opacity-40"></div>
            <div class="absolute -top-10 -left-10 w-32 h-32 bg-sky-400 rounded-full opacity-30"></div>
        </div>
    </section>

    <footer class="bg-white py-8 border-t border-sky-100 shadow-md">
        <div class="max-w-7xl mx-auto px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <i data-lucide="graduation-cap" class="h-5 w-5 text-sky-500"></i>
                <span class="text-sky-950 font-bold tracking-wide">EduMonitor</span>
            </div>
            <p class="text-slate-500 text-sm text-center sm:text-right font-medium">
                &copy; {{ date('Y') }} EduMonitor &bull;  
            </p>
        </div>
    </footer>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>