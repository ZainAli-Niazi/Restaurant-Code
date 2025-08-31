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

        <header class="main-header">
            <h3 class="fw-bold mb-0">@yield('header')</h3>
            <div class="user-dropdown">
                <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <strong>{{ ucfirst(Auth::user()->username) }}</strong>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="{{ route('admin.logout') }}"><i
                                class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
            </div>
        </header>




        {{-- Main Content --}}
        <div class="main-content">
            @yield('content')
        </div>
    </div>
    @yield('styles')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Sidebar Script -->
    <script src="{{ asset('assets/js/app.js') }}"></script>


    @yield('scripts')

    <script>
        document.addEventListener("keydown", function(event) {
            if (event.key === "F1") {
                event.preventDefault(); // Prevent browser default F1 (help)
                window.location.href = "{{ url('/pos') }}"; // Go to homepage
            }
        });
    </script>
      <div class="footer">
            2025 © Niazi School MANAGEMENT Developed By <a href="#" class="text-decoration-none">Zain Ali</a>
        </div>
    </div>
</body>

</html>
