<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        if (localStorage.getItem('isDark') === 'true' || (!('isDark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="h-full font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-900 selection:bg-primary-500 selection:text-white">

    <div class="min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden">
        
        {{-- Background Blobs --}}
        <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-primary-400/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-green-400/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        </div>

        <div class="w-full max-w-lg text-center">
            {{-- Logo --}}
            <div class="flex justify-center mb-8">
                <x-application-logo class="h-20 w-auto text-primary-600 dark:text-primary-400" />
            </div>

            {{-- Glass Card --}}
            <div class="relative backdrop-blur-xl bg-white/60 dark:bg-gray-800/60 rounded-3xl shadow-2xl ring-1 ring-gray-900/5 dark:ring-white/10 p-10 overflow-hidden transform transition-all hover:scale-[1.01]">
                
                {{-- Decorative Top Border --}}
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary-500 to-green-500"></div>

                @yield('content')
                
            </div>

            {{-- Footer / Links --}}
            <div class="mt-8 text-sm text-gray-500 dark:text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>
    </div>

</body>
</html>
