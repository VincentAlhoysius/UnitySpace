<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'UnitySpace') }} - Kampus Harmonis Inklusif</title>

        <!-- Fonts (Outfit or Inter) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-300">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Flash Session Alerts (Premium Slide-in Toasts) -->
            @if(session('success') || session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     x-transition:enter="transform ease-out duration-300 transition" 
                     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" 
                     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0" 
                     x-transition:leave="transition ease-in duration-200" 
                     x-transition:leave-start="opacity-100" 
                     x-transition:leave-end="opacity-0"
                     class="fixed bottom-5 right-5 z-50 max-w-md w-full bg-white dark:bg-slate-900 shadow-2xl rounded-2xl border-l-8 overflow-hidden pointer-events-auto flex p-4 {{ session('success') ? 'border-emerald-500' : 'border-rose-500' }}">
                    <div class="flex-shrink-0">
                        @if(session('success'))
                            <i class="fa-solid fa-circle-check text-emerald-500 text-2xl"></i>
                        @else
                            <i class="fa-solid fa-circle-exclamation text-rose-500 text-2xl"></i>
                        @endif
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ session('success') ? 'Berhasil' : 'Peringatan' }}
                        </p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            {{ session('success') ?? session('error') }}
                        </p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false" class="inline-flex text-slate-400 hover:text-slate-500">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 shadow-sm transition-colors duration-300">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 max-w-7xl w-full mx-auto py-8 px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white dark:bg-slate-900 border-t border-slate-150 dark:border-slate-800 transition-colors duration-300 py-6 mt-12 text-center text-sm text-slate-500 dark:text-slate-400">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
                    <p>&copy; {{ date('Y') }} UnitySpace. Kampus Harmonis, Toleransi Nyata. (SDG 10 & SDG 16)</p>
                    <div class="flex gap-4">
                        <a href="{{ route('home') }}" class="hover:text-indigo-500 transition">Tentang Kami</a>
                        <a href="{{ route('reports.create') }}" class="hover:text-indigo-500 transition">Lapor Isu SARA</a>
                        <a href="{{ route('badges.index') }}" class="hover:text-indigo-500 transition">Leaderboard</a>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
