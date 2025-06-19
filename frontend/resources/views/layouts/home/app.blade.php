<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TaniDirect - Pasar Tani Digital')</title>
    <link rel="stylesheet" href="{{ asset('template/home/css/home-styles.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('head')
</head>
<body>
    @include('layouts.home.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('layouts.home.partials.footer')

    <script src="{{ asset('template/home/js/home-script.js') }}"></script>
    @stack('scripts')
    
</body>
</html>
