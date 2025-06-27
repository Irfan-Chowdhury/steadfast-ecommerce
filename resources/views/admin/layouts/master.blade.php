<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 4 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .wrapper {
            display: flex;
            flex: 1;
        }

        .sidebar {
            width: 220px;
            background: #343a40;
            color: #fff;
            min-height: 100vh;
        }

        .sidebar a {
            color: #fff;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
        }

        .sidebar a:hover, .sidebar .active {
            background-color: #495057;
        }

        .content {
            flex: 1;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .footer {
            background: #343a40;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        .navbar {
            margin-bottom: 0;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Stead Fast</a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span class="nav-link text-light" id="user-name">{{ auth()->user() ? auth()->user()->name : "" }}</span>
            </li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button href="#" class="nav-link btn btn-danger text-light" id="logoutBtn">Logout</button>
                </form>
            </li>
        </ul>
    </nav>

    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <h5 class="text-center py-3">Admin Menu</h5>
            <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">📊 Dashboard</a>
            <a href="/products" class="{{ request()->is('products') ? 'active' : '' }}">👥 Products</a>
            <a href="/sales" class="{{ request()->is('sales') ? 'active' : '' }}">👥 Sales</a>
            <a href="/reports" class="{{ request()->is('reports') ? 'active' : '' }}">📈 Reports</a>
        </div>

        <!-- Content Area -->
        <div class="content">
            @yield('admin_content')
        </div>
    </div>

    <br>
    <!-- Footer -->
    <div class="footer">
        &copy; {{ date('Y') }} Ecommerce | All rights reserved.
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @stack('admin_scripts')

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
            });


            function confirmDelete(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${id}`).submit();
                    }
                });
            }
        </script>
    @endif

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                html: `
                    <ul style="text-align: center; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
            });
        </script>
    @endif

</body>
</html>
