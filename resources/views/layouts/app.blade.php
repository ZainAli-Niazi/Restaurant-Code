<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'KEY-POS')</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pos-screen.css') }}" />

</head>

<body>
    <div class="full-wrapper">
      
 
 <header class="main-header d-flex align-items-center justify-content-between">
    <!-- Left: Logo + Restaurant Name -->
    <div class="d-flex align-items-center">
        @if (!empty($restaurantSettings['restaurant_logo']))
            <img src="{{ asset('storage/' . $restaurantSettings['restaurant_logo']) }}" alt="Logo"
                class="me-2" style="height:42px; width:auto; border-radius:8px;" />
        @endif

        <h3 class="fw-bold mb-0">{{ strtoupper($restaurantSettings['restaurant_name'] ?? '') }}</h3>
    </div>

    

    <!-- Right: User Dropdown -->
    <div class="user-dropdown dropdown">

        <!-- Center: Header Icons -->
    <div class="header-icons d-flex align-items-center gap-3">
        <!-- Notification Icon -->
        <button class="icon-btn" type="button">
            <i class="bi bi-bell-fill"></i>  
        </button>

        <!-- Widget Icon -->
        <button class="icon-btn" type="button">
            <i class="bi bi-grid-fill"></i>
        </button>
    </div>
        <button class="btn btn-user dropdown-toggle d-flex align-items-center" type="button" id="userDropdown"
            data-bs-toggle="dropdown" aria-expanded="false">
            <strong>{{ ucfirst(Auth::user()->username) }}</strong>
        </button>


        
        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>

            <!-- POS -->
            <li>
                <a href="{{ route('pos.index') }}" class="dropdown-item">
                    <i class="bi bi-cash-coin me-2"></i> POS
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>

            <!-- Orders -->
            <li>
                <a href="{{ route('orders.index') }}" class="dropdown-item">
                    <i class="bi bi-cart4 me-2"></i> Orders
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>

            <!-- Products -->
            <li>
                <a href="{{ route('products.index') }}" class="dropdown-item">
                    <i class="bi bi-box-seam me-2"></i> Products
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>

            <!-- Shifts -->
            <li>
                <a href="{{ route('shifts.index') }}" class="dropdown-item">
                    <i class="bi bi-clock-history me-2"></i> Shifts
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>

            <!-- Reports (Submenu) -->
            <li class="dropdown-submenu">
                <a href="#" class="dropdown-item dropdown-toggle">
                    <i class="bi bi-bar-chart me-2"></i> Reports
                </a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('reports.sales') }}" class="dropdown-item">Sales Report</a></li>
                    <li><a href="{{ route('reports.expenses') }}" class="dropdown-item">Expense Report</a></li>
                    <li><a href="{{ route('reports.profit-loss') }}" class="dropdown-item">Profit & Loss</a></li>
                    <li><a href="{{ route('reports.products') }}" class="dropdown-item">Product Report</a></li>
                </ul>
            </li>

            <li><hr class="dropdown-divider"></li>

            <!-- Expenses -->
            <li>
                <a href="{{ route('expenses.index') }}" class="dropdown-item">
                    <i class="bi bi-currency-dollar me-2"></i> Expenses
                </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            <!-- Settings -->
            <li>
                <a href="{{ route('settings.index') }}" class="dropdown-item">
                    <i class="fas fa-cog me-2"></i> Settings
                </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            <!-- Logout -->
            <li>
                <a href="{{ route('admin.logout') }}" class="dropdown-item text-danger">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</header>







        {{-- Main Content --}}
        <div class="main-content">
            @yield('content')
        </div>
    </div>
    @yield('styles')


    <!-- Custom Sidebar Script -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!-- Bootstrap & Chart.js -->
    <script src="{{ asset('assets/js/bootstrap.cdn.js') }}"></script>
    <script src="{{ asset('assets/js/chart.js') }}"></script>

    @yield('scripts')

    <script>
        document.addEventListener("keydown", function(event) {
            if (event.key === "F1") {
                event.preventDefault(); // Prevent browser default F1 (help)
                window.location.href = "{{ url('/pos') }}"; // Go to homepage
            }
        });
        document.addEventListener("keydown", function(event) {
            if (event.key === "F2") {
                event.preventDefault(); // Prevent browser default F1 (help)
                window.location.href = "{{ url('/orders') }}"; // Go to homepage
            }
        });
    </script>

    </div>
</body>

</html>
