<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Monitoring System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#dff3ff]">

    <!-- NAVBAR -->
    <nav class="w-full py-6">
        <div class="max-w-7xl mx-auto bg-white rounded-full px-8 py-4 flex justify-between items-center shadow-lg">

            <h1 class="text-2xl font-bold text-sky-500">
                EduMonitor
            </h1>

            <ul class="hidden md:flex gap-8 text-gray-700 font-medium">
                <li><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Features</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Contact</a></li>
            </ul>

            <div class="flex gap-4">
                <a href="/login" class="text-sky-500 font-semibold">
                    Login
                </a>

                <a href="#"
                   class="bg-sky-400 hover:bg-sky-500 text-white px-5 py-2 rounded-full shadow">
                    Get Started
                </a>
            </div>

        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="max-w-7xl mx-auto px-6 py-10">

        <div class="bg-sky-300 rounded-[40px] p-10 md:p-16 grid md:grid-cols-2 items-center gap-10 relative overflow-hidden shadow-xl">

            <!-- Left -->
            <div>

                <p class="text-white text-sm mb-4">
                    Academic Monitoring Platform
                </p>

                <h2 class="text-5xl font-extrabold text-white leading-tight">
                    Web-Based Academic & Performance
                     Monitoring System <br>
                     
                </h2>

                <p class="mt-6 text-white text-lg">
                    Empowering schools to manage academic performance,
                    attendance and student records digitally.
                </p>

                <div class="mt-8 flex gap-4">

                    <a href="#"
                       class="bg-white text-sky-500 px-6 py-3 rounded-full font-semibold shadow-md">
                        Get Free Demo
                    </a>

                    <a href="#"
                       class="bg-sky-100 text-sky-600 px-6 py-3 rounded-full font-semibold">
                        See Features
                    </a>

                </div>

            </div>

            <!-- Right -->
            <div class="relative">

                <img
                    src="https://images.unsplash.com/photo-1509062522246-3755977927d7"
                    alt="students"
                    class="rounded-3xl shadow-2xl w-full object-cover"
                >

            </div>

            <!-- Circle decorations -->
            <div class="absolute top-10 right-20 w-72 h-72 border-[20px] border-sky-200 rounded-full opacity-40"></div>

            <div class="absolute bottom-0 left-1/2 w-40 h-40 bg-sky-200 rounded-full opacity-30"></div>

        </div>

    </section>

    <!-- STATS -->
    <section class="max-w-7xl mx-auto px-6 py-10">

        <div class="bg-white rounded-[40px] p-10 shadow-lg">

            <div class="grid md:grid-cols-3 gap-8 text-center">

                <div>
                    <h3 class="text-4xl font-bold text-sky-500">
                        100+
                    </h3>

                    <p class="text-gray-600 mt-2">
                        Primary Schools
                    </p>
                </div>

                <div>
                    <h3 class="text-4xl font-bold text-sky-500">
                        20,000+
                    </h3>

                    <p class="text-gray-600 mt-2">
                        Students Managed
                    </p>
                </div>

                <div>
                    <h3 class="text-4xl font-bold text-sky-500">
                        500+
                    </h3>

                    <p class="text-gray-600 mt-2">
                        Teachers Connected
                    </p>
                </div>

            </div>

        </div>

    </section>

    <!-- FEATURES -->
    <section class="max-w-7xl mx-auto px-6 pb-20">

        <h2 class="text-4xl font-bold text-center text-gray-800 mb-12">
            Why EduMonitor
        </h2>

        <div class="grid md:grid-cols-4 gap-6">

            <div class="bg-white p-8 rounded-3xl shadow-md">
                <div class="text-4xl">📚</div>

                <h3 class="mt-4 text-xl font-bold text-gray-800">
                    Student Records
                </h3>

                <p class="mt-3 text-gray-600">
                    Manage student information digitally.
                </p>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-md">
                <div class="text-4xl">📈</div>

                <h3 class="mt-4 text-xl font-bold text-gray-800">
                    Performance Tracking
                </h3>

                <p class="mt-3 text-gray-600">
                    Monitor academic progress easily.
                </p>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-md">
                <div class="text-4xl">🧑‍🏫</div>

                <h3 class="mt-4 text-xl font-bold text-gray-800">
                    Teacher Management
                </h3>

                <p class="mt-3 text-gray-600">
                    Organize teacher records efficiently.
                </p>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-md">
                <div class="text-4xl">📝</div>

                <h3 class="mt-4 text-xl font-bold text-gray-800">
                    Reports
                </h3>

                <p class="mt-3 text-gray-600">
                    Generate academic reports instantly.
                </p>
            </div>

        </div>

    </section>

</body>
</html>
