<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel')) - MHD print lab</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    @stack('styles')
</head>
<body>
    @include('layouts.navigation')
    
    <div class="container-fluid py-4 min-vh-100 bg-light">
        @if (isset($header))
            <header class="bg-white shadow-sm mb-4 rounded">
                <div class="container">
                    <h1 class="h3 py-3 m-0">{{ $header }}</h1>
                </div>
            </header>
        @endif
        
        <main class="main-content">
            @yield('content')
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>
