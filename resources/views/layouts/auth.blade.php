<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auth | {{ config('app.name') }}</title>

    <!-- Argon CSS -->
    <link href="{{ asset('argonpro/assets/vendor/nucleo/css/nucleo.css') }}" rel="stylesheet">
    <link href="{{ asset('argonpro/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('argonpro/assets/css/argon.css') }}" rel="stylesheet">
</head>
<body class="bg-default">

    <div class="main-content">
        @yield('content')
    </div>

    <!-- Core JS -->
    <script src="{{ asset('argonpro/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('argonpro/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('argonpro/assets/js/argon.js') }}"></script>
</body>
</html>
