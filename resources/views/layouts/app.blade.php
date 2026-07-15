<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Sidebar styles – only used when admin */
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background: #343a40;
            padding-top: 70px;
            transition: 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar .nav-link {
            color: #fff;
            padding: 12px 20px;
            border-radius: 0;
        }
        .sidebar .nav-link:hover {
            background: #495057;
        }
        .sidebar .nav-link i {
            width: 24px;
            margin-right: 10px;
        }
        .sidebar .nav-link.active {
            background: #495057;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            transition: 0.3s;
        }
        .hamburger {
            display: none;
            background: #343a40;
            border: none;
            color: #fff;
            font-size: 28px;
            padding: 8px 16px;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1100;
            border-radius: 4px;
            cursor: pointer;
        }
        .hamburger:hover {
            background: #495057;
        }
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
            }
            .sidebar.active {
                left: 0;
            }
            .content {
                margin-left: 0;
            }
            .hamburger {
                display: block;
            }
        }
        .top-nav {
            background: #f8f9fa;
            padding: 10px 20px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        .no-sidebar-content {
            padding: 20px;
        }
    </style>
</head>
<body>
    @auth
        @if(auth()->user()->role === 'admin')
            <!-- Hamburger button (only for admin) -->
            <button class="hamburger" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Sidebar (only for admin) -->
            <div class="sidebar" id="sidebar">
                <nav class="nav flex-column">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    @if(Route::has('admin.users.index'))
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> Users
                        </a>
                    @endif
                    @if(Route::has('admin.classes.index'))
                        <a href="{{ route('admin.classes.index') }}" class="nav-link {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                            <i class="fas fa-school"></i> Classes
                        </a>
                    @endif
                    @if(Route::has('admin.sections.index'))
                        <a href="{{ route('admin.sections.index') }}" class="nav-link {{ request()->routeIs('admin.sections.*') ? 'active' : '' }}">
                            <i class="fas fa-layer-group"></i> Sections
                        </a>
                    @endif
                    @if(Route::has('admin.fee-structures.index'))
                        <a href="{{ route('admin.fee-structures.index') }}" class="nav-link {{ request()->routeIs('admin.fee-structures.*') ? 'active' : '' }}">
                            <i class="fas fa-coins"></i> Fee Structure
                        </a>
                    @endif
                    @if(Route::has('admin.invoices.index'))
                        <a href="{{ route('admin.invoices.index') }}" class="nav-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice"></i> Invoices
                        </a>
                    @endif
                    <hr style="border-color: #495057;">
                    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                </nav>
            </div>

            <!-- Main content with sidebar -->
            <div class="content">
                <div class="top-nav d-flex justify-content-between">
                    <span>Welcome, {{ auth()->user()->name }}</span>
                    <span>Role: {{ ucfirst(auth()->user()->role) }}</span>
                </div>
                @yield('content')
            </div>

            <script>
                document.getElementById('sidebarToggle').addEventListener('click', function() {
                    document.getElementById('sidebar').classList.toggle('active');
                });
                // Close sidebar on outside click on mobile
                document.addEventListener('click', function(event) {
                    const sidebar = document.getElementById('sidebar');
                    const toggle = document.getElementById('sidebarToggle');
                    if (window.innerWidth <= 768 &&
                        !sidebar.contains(event.target) &&
                        !toggle.contains(event.target)) {
                        sidebar.classList.remove('active');
                    }
                });
            </script>
        @else
            <!-- Non-admin layout: no sidebar -->
            <div class="container">
                <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="{{ route('dashboard') }}">{{ config('app.name') }}</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item">
                                    <span class="navbar-text me-3">Welcome, {{ auth()->user()->name }}</span>
                                </li>
                                <li class="nav-item">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <div class="no-sidebar-content">
                    @yield('content')
                </div>
            </div>
        @endif
    @else
        <!-- Public layout (no sidebar) -->
        <div class="container mt-4">
            @yield('content')
        </div>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>