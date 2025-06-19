{{-- resources/views/layouts/dashboard/main.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Dashboard Pasar Tani' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts and Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded">
    <link href="{{ asset('template/dashboard/css/nucleo-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('template/dashboard/css/nucleo-svg.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('template/dashboard/img/LogoDashboard.png') }}">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link id="pagestyle" href="{{ asset('template/dashboard/css/material-dashboard.css') }}" rel="stylesheet">
</head>
<body class="g-sidenav-show bg-gray-100">


    <!-- Sidebar -->
    @include('layouts.dashboard.partials.sidebar', ['role' => $role])

    <!-- Main Content -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('layouts.dashboard.partials.navbar', ['title' => $title ?? 'Dashboard'])
        <div class="container-fluid py-4">
            @yield('content')
        </div>
    </main>

    <!-- Core JS Files -->
    <script src="{{ asset('template/dashboard/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('template/dashboard/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/dashboard/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('template/dashboard/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('template/dashboard/js/plugins/chartjs.min.js') }}"></script>
    <script src="{{ asset('template/dashboard/js/material-dashboard.min.js') }}"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

     <!-- Tambahkan SweetAlert2 & Global.js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('SweetAlert/global.js') }}"></script>
    
    <!-- Script Show Alert Otomatis dari Session -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if(session('success'))
                showSuccessMessage(@json(session('success')));
            @endif

            @if($errors->has('error'))
                showErrorMessage(@json($errors->first('error')));
            @endif
        });
    </script>

    @stack('scripts') <!-- Jika ada js tambahan per halaman -->
</body>
</body>
</html>
