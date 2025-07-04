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
<body class="bg-light-subtle">
<main class="min-vh-100">
    <div class="container py-5">
        {{-- Auth card --}}
        <div class="card border-2 border-primary-subtle mx-auto"
             style="width: 360px; max-width: 100%; --dc-card-box-shadow: var(--dc-box-shadow);">
            <div class="card-header border-2 border-primary-subtle bg-primary-subtle text-center">
                <a href="{{ route('welcome') }}">
                    <x-application-logo style="fill: var(--dc-primary); width: 50%; height: auto" class="w-50" />
                </a>
            </div>

            <div class="card-body">
                {{ $slot }}
            </div>
        </div>
    </div>
</main>
</body>
</html>
