<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    {{-- Scripts --}}
    @vite(['resources/js/app.js'])
    @stack('head-scripts')
</head>
<body>
{{-- Navigation --}}
@include('layouts.navbar')

{{-- Page Heading --}}
@isset($header)
    <header>
        <div class="container py-4">
            {{ $header }}
        </div>
    </header>
@endisset

{{-- Page Content --}}
<main class="min-vh-100">
    {{ $slot }}
</main>

{{-- Footer --}}
<footer class="bg-dark text-light border-top border-dark-subtle">
    <div class="container py-5">
        <p class="text-center">
            <strong>FOOTER</strong>
            <br><br>
            <small>
                &copy; {{ now()->year }} {{ config('app.name', 'Laravel') }}
            </small>
        </p>
    </div>
</footer>

{{-- Modals --}}
@stack('modals')

{{-- Scripts --}}
@stack('bottom-scripts')
</body>
</html>
