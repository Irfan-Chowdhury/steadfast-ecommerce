<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Time Tracker')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="text-center mb-4 text-secondary">Steadfast E-Commerce</h2>
        @yield('auth-content')
    </div>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @stack('auth_scripts')

</body>
</html>