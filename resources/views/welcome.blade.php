<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ZoikoAdmin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Tailwind via CDN (for simplicity) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white">

    <div class="min-h-screen flex flex-col justify-center items-center px-4">
        <!-- Laravel Logo -->
        <div class="mb-6">
            <svg class="h-16 w-16 text-red-500" fill="none" viewBox="0 0 62 65" xmlns="http://www.w3.org/2000/svg">
                <path fill="#FF2D20" d="M61.8548 14.6253...Z" />
            </svg>
        </div>

        <!-- Welcome Title -->
        <h1 class="text-4xl font-bold mb-4">ZoikoAdmin</h1>
        <p class="text-lg text-gray-600 dark:text-gray-300 text-center max-w-xl mb-8">
            Welcome to ZoikoAdmin panel. Please sign in by below link.
        </p>

        <!-- Buttons Section -->
        <div class="flex flex-wrap justify-center gap-4 mb-12">

		    <a href="{{ route('filament.admin.auth.login') }}" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-5 py-2.5 rounded-lg shadow transition">
                🔐 Admin Login
            </a>
        </div>

        <!-- Laravel Version Footer -->
        <div class="text-sm text-gray-500 dark:text-gray-400 mt-auto">
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
        </div>
    </div>
</body>
</html>
