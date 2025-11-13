<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />

    <!-- PWA  -->
    <meta name="theme-color" content="#6777ef"/>
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Document</title>
</head>
<body class="bg-gray-100">

    <main class="pb-24"> <!-- padding-bottom agar konten tidak tertutup nav -->
        @unless (Route::is('admin.karyawan.index', 'admin.karyawan.create', 'admin.karyawan.edit', 'admin.profile.index', 'admin.agenda.index'))
            @include('layouts.partials.adminnavtop')
        @endunless
        {{ $slot }}

        @unless (Route::is('admin.karyawan.show'))
            {{-- Panggil partial navigasi admin DI DALAM @unless --}}
            @include('layouts.partials.adminnav')
        @endunless



    </main>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flowbite-datepicker@1.4.2/dist/datepicker.min.js"></script>
        <script src="https://unpkg.com/flowbite-datepicker@1.3.0/js/datepicker-full.min.js"></script>
        <script src="https://unpkg.com/flowbite-datepicker@1.3.0/js/locales/id.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @stack('scripts')

        {{-- PWA --}}
        <script src="{{ asset('/sw.js') }}"></script>
        <script>
        if ("serviceWorker" in navigator) {
            // Register a service worker hosted at the root of the
            // site using the default scope.
            navigator.serviceWorker.register("/sw.js").then(
            (registration) => {
                console.log("Service worker registration succeeded:", registration);
            },
            (error) => {
                console.error(`Service worker registration failed: ${error}`);
            },
            );
        } else {
            console.error("Service workers are not supported.");
        }
        </script>

</body>
</html>
