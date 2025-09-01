<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 12 | Admin Login</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .card-header {
            background-color: #fff;
            border-bottom: none;
            text-align: center;
            padding: 1.2rem 1rem;
        }

        .card-header h4 {
            margin: 0;
            font-weight: 700;
            color: #0d6efd;
            font-size: 24px;
        }

        .btn-primary {
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
        }

        .form-floating>.form-control,
        .form-floating>.form-select {
            border-radius: 10px;
        }

        .alert {
            border-radius: 10px;
            font-size: 14px;
        }

        .divider {
            height: 1px;
            background-color: #dee2e6;
            margin: 1.5rem 0;
        }
    </style>
</head>

<body>
    <section class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card">
                        <!-- Card Header -->
                        <div class="card-header">
                            <h4>Login Here</h4>
                        </div>

                        <div class="card-body p-4">

                            <!-- Session Messages -->
                            @if (Session::has('success'))
                                <div class="alert alert-success">{{ Session::get('success') }}</div>
                            @endif
                            @if (Session::has('error'))
                                <div class="alert alert-danger">{{ Session::get('error') }}</div>
                            @endif

                            <!-- Login Form -->
                            <form method="POST" action="{{ route('admin.authenticate') }}" novalidate>
                                @csrf
                                <div class="row gy-3">

                                    <!-- Username -->
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" name="username" id="username"
                                                class="form-control @error('username') is-invalid @enderror"
                                                placeholder="Enter username" value="{{ old('username') }}" required>
                                            <label for="username">Username</label>
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="password" name="password" id="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Enter password" required>
                                            <label for="password">Password</label>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-box-arrow-in-right me-2"></i> Login Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Divider -->
                            <div class="divider"></div>

                        </div>
                    </div> <!-- Card End -->
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
