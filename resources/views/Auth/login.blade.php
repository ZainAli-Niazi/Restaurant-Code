<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> {{ config('app.name') }} | Admin Login</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        /* Body Styling */
        body {
            background: #e0e5ec;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Login Container */
        .login-box {
            background: #e0e5ec;
            padding: 40px 30px;
            width: 400px;
            min-height: 380px; /* Increased height */
            border-radius: 20px;
            box-shadow:
                8px 8px 15px #b8b9be,
                -8px -8px 15px #ffffff;
            text-align: center;
        }

        /* Heading */
        .login-box h4 {
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

        /* Login Button */
        .btn-login {
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

        .btn-login:hover {
            box-shadow:
                inset 4px 4px 8px #b8b9be,
                inset -4px -4px 8px #ffffff;
        }

        /* Validation Errors */
        .invalid-feedback {
            text-align: left;
            font-size: 13px;
            margin-top: 4px;
        }

        /* Alerts */
        .alert {
            border-radius: 10px;
            font-size: 14px;
            padding: 8px 10px;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <!-- Heading -->
        <h4>Login Here</h4>

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

            <!-- Submit Button -->
            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>
</body>
</html>
