<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'KEY-POS')</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/pos-screen.css') }}" />

</head>

<body>
    <div class="full-wrapper">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

     <header class="main-header d-flex align-items-center justify-content-between p-2">
            <!-- Left side: Logo + Restaurant Name -->
            <div class="d-flex align-items-center">
                @if (!empty($restaurantSettings['restaurant_logo']))
                    <img src="{{ asset('storage/' . $restaurantSettings['restaurant_logo']) }}" alt="Logo"
                        class="me-2" style="height:40px; width:auto;">
                @endif

                <h3 class="fw-bold mb-0">{{ strtoupper($restaurantSettings['restaurant_name'] ?? '') }}</h3>
            </div>

            <!-- Right side: User dropdown -->
            <div class="user-dropdown">
                <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <strong>{{ ucfirst(Auth::user()->username) }}</strong>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('settings.index') }}">
                            <i class="fas fa-cog me-2"></i> Settings
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.logout') }}">
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
    <script src="{{ asset('assets/js/bootstrap.cdn.js')}}"></script>
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

 