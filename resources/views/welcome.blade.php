<!DOCTYPE html>
<html data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
         @vite(['resources/css/app.css', 'resources/js/app.js'])
         <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Document</title>
    <!-- PWA  -->
    <meta name="theme-color" content="#6777ef"/>
    <link rel="apple-touch-icon" href="{{ asset('digitaliz.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">


    <main class="flex-grow pb-56">
        <h1>Hello</h1>

        <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    </main>


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
