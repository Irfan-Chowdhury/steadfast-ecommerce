<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 4 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
        <a class="navbar-brand" href="#">🕒 Time Tracker</a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span class="nav-link text-light" id="user-name">{{ auth()->user()->name }}</span>
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
            <a href="/users" class="{{ request()->is('users') ? 'active' : '' }}">👥 Users</a>
            <a href="/profile" class="{{ request()->is('profile') ? 'active' : '' }}">🙍 Profile</a>
            <a href="/clients" class="{{ request()->is('clients') ? 'active' : '' }}">🏢 Clients</a>
            <a href="/projects" class="{{ request()->is('projects') ? 'active' : '' }}">📁 Projects</a>
            <a href="/time-logs" class="{{ request()->is('time-logs') ? 'active' : '' }}">🕓 Time Logs</a>
            <a href="/reports" class="{{ request()->is('reports') ? 'active' : '' }}">📈 Reports</a>
        </div>

        <!-- Content Area -->
        <div class="content">
            @yield('admin_content')
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; {{ date('Y') }} Time Tracker | All rights reserved.
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @stack('admin_scripts')
</body>
</html>
