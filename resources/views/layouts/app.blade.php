<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

    {{-- NAVBAR --}}
    @include('components.navbar')

    <main class="pt-4">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('components.footer')
</body>
</html>
