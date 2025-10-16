<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} | Admin Register</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        /* Body Styling */
        body {
            background-image: url('{{ asset('assets/login-img/17973908.jpg') }}');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        /* Register Container */
        .register-box {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 50px 40px;
            width: 450px;
            min-height: 520px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Heading */
        .register-box h4 {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 30px;
            letter-spacing: 0.5px;
        }

        /* Input Groups */
        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group input {
            width: 100%;
            padding: 15px 50px 15px 20px;
            border: 2px solid #e8ecf4;
            outline: none;
            background: #ffffff;
            border-radius: 50px;
            font-size: 15px;
            color: #2d3748;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .input-group input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        /* Input Icons */
        .input-group i {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .input-group input:focus + i {
            color: #667eea;
        }

        /* Register Button */
        .btn-register {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            width: 100%;
            padding: 15px;
            border-radius: 50px;
            font-weight: 600;
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            margin-top: 10px;
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        /* Validation Errors */
        .invalid-feedback {
            text-align: left;
            font-size: 13px;
            margin-top: 8px;
            margin-left: 20px;
            color: #e53e3e;
            font-weight: 500;
        }

        .is-invalid {
            border-color: #e53e3e !important;
        }

        .is-invalid + i {
            color: #e53e3e !important;
        }

        /* Alerts */
        .alert {
            border-radius: 12px;
            font-size: 14px;
            padding: 12px 15px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: rgba(229, 62, 62, 0.15);
            color: #c53030;
        }

        .alert ul {
            margin-bottom: 0;
            padding-left: 20px;
        }

        .alert li {
            text-align: left;
            margin-bottom: 5px;
        }

        .alert li:last-child {
            margin-bottom: 0;
        }

        /* Login Link */
        .login-link {
            display: block;
            margin-top: 25px;
            font-size: 14px;
            text-decoration: none;
            color: #667eea;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .login-link:hover {
            color: #764ba2;
            text-decoration: underline;
            transform: translateY(-1px);
        }

        /* Optional: Add a subtle animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-box {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>

<body>
    <div class="register-box">
        <!-- Heading -->
        <h4>Create Account</h4>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Register Form -->
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
        <a href="{{ route('admin.login') }}" class="login-link">
            Already have an account? Login here
        </a>
    </div>
</body>
</html>