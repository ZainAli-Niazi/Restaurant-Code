<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 12 | Admin Register</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        body {
            background: #e0e5ec;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Register Container */
        .register-box {
            background: #e0e5ec;
            padding: 40px 30px;
            width: 420px;
            min-height: 470px;
            border-radius: 20px;
            box-shadow:
                8px 8px 15px #b8b9be,
                -8px -8px 15px #ffffff;
            text-align: center;
        }

        /* Heading */
        .register-box h4 {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 25px;
        }

        /* Input Groups */
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group input {
            width: 100%;
            padding: 12px 45px 12px 15px;
            border: none;
            outline: none;
            background: #e0e5ec;
            border-radius: 50px;
            box-shadow:
                inset 6px 6px 10px #b8b9be,
                inset -6px -6px 10px #ffffff;
            font-size: 14px;
            color: #333;
        }

        /* Input Icons */
        .input-group i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            font-size: 18px;
        }

        /* Register Button */
        .btn-register {
            background: #e0e5ec;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 50px;
            font-weight: 600;
            color: #333;
            font-size: 16px;
            box-shadow:
                6px 6px 10px #b8b9be,
                -6px -6px 10px #ffffff;
            transition: 0.2s ease-in-out;
        }

        .btn-register:hover {
            box-shadow:
                inset 4px 4px 8px #b8b9be,
                inset -4px -4px 8px #ffffff;
        }

        /* Error & Alerts */
        .invalid-feedback {
            text-align: left;
            font-size: 13px;
            margin-top: 4px;
        }

        .alert {
            border-radius: 10px;
            font-size: 14px;
            padding: 8px 10px;
        }

        /* Login Link */
        .login-link {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            text-decoration: none;
            color: #007bff;
            font-weight: 500;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="register-box">
        <h4>Register Here</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.store') }}">
            @csrf

            <!-- Username -->
            <div class="input-group">
                <input type="text" name="username" id="username"
                    class="@error('username') is-invalid @enderror"
                    placeholder="Enter username" value="{{ old('username') }}" required>
                <i class="fas fa-user"></i>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="input-group">
                <input type="password" name="password" id="password"
                    class="@error('password') is-invalid @enderror"
                    placeholder="Enter password" required>
                <i class="fas fa-lock"></i>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="input-group">
                <input type="password" name="password_confirmation" id="password_confirmation"
                    placeholder="Confirm password" required>
                <i class="fas fa-check-circle"></i>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-register">Register</button>
        </form>

        <!-- Link to Login -->
        <a href="{{ route('admin.login') }}" class="login-link">Already have an account? Login</a>
    </div>
</body>
</html>
