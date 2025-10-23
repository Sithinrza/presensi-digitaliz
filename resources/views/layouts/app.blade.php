<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
         @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Document</title>
</head>
<body class="bg-gray-100">

    <main class="pb-24"> <!-- padding-bottom agar konten tidak tertutup nav -->
        {{ $slot }}
    </main>

    @auth
        @if (Auth::user()->role === 'admin')
            {{-- Ini akan memuat file _admin-nav.blade.php --}}
            @include('layouts.partials.adminnav')
        @elseif (Auth::user()->role === 'karyawan')
            {{-- Ini akan memuat file _karyawan-nav.blade.php --}}
            @include('layouts.partials.karyawannav')
        @endif
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    @stack('scripts')
    
</body>
</html>