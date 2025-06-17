<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Pasar Tani Digital')</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('template/Auth/css/auth-styles.css') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        @yield('content')
    </div>

    <!-- JS Global SweetAlert dan script lain -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('SweetAlert/global.js') }}"></script>   
    <!-- Script Validasi Form Register -->
    <script src="{{ asset('SweetAlert/formAuth/form-validation.js') }}"></script>

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
    

    @stack('scripts')
</body>
</html>
