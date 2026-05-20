<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-sky-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-10 rounded-[30px] shadow-xl w-full max-w-md">

        <div class="text-center">

            <h1 class="text-3xl font-bold text-sky-500">
                EduMonitor
            </h1>

            <p class="text-gray-500 mt-2">
                Academic & Performance Monitoring System
            </p>

        </div>

        <form class="mt-8">

            <div class="mb-5">
                <label class="block text-gray-700 mb-2">
                    Email Address
                </label>

                <input
                    type="email"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400"
                    placeholder="Enter email"
                >
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 mb-2">
                    Password
                </label>

                <input
                    type="password"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400"
                    placeholder="Enter password"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-sky-500 hover:bg-sky-600 text-white py-3 rounded-xl font-semibold shadow-lg"
            >
                Login
            </button>

        </form>

        <div class="mt-6 text-center text-gray-500 text-sm">
            Primary School Academic Management System
        </div>

    </div>

</body>
</html>