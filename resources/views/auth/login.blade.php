<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('img/logo-iflab.png') }}">
    <title>Inventory Website</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-card {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            border-top: 5px solid #f7d200;
        }

        .login-card h2 {
            margin-bottom: 14px;
            font-weight: 600;
            color: #003078;
            text-align: center;
        }

        .logo-wrapper {
            text-align: center;
            margin-bottom: 22px;
        }

        .iflabs-logo {
            max-width: 120px;
            height: auto;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #003078;
        }

        .form-group input {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: 0.3s;
        }

        .form-group input:focus {
            border-color: #f7d200;
            outline: none;
            box-shadow: 0 0 0 2px rgba(247, 210, 0, 0.3);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            width: 100%;
            padding-right: 40px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: #003078;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            margin-top: 14px;
            transition: background 0.3s;
        }

        .login-btn:hover {
            background: #00225b;
        }

        .footer-text {
            margin-top: 20px;
            font-size: 14px;
            text-align: center;
            color: #666;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
            }
        }

        .signup-text {
            margin-top: 32px;
            text-align: center;
            font-size: 14px;
            color: #333;
        }

        .signup-text a {
            color: #003078;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s;
        }

        .signup-text a:hover {
            color: #f7d200;
        }

    </style>
</head>
<body>
    <div class="login-card">
        <!-- Tambahkan logo di bawah heading -->
        <div class="logo-wrapper">
            <img src="{{ asset('img/iflabs.png') }}" alt="IF Labs Logo" class="iflabs-logo">
        </div>

        <h2>Welcome</h2>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            @if(session('success'))
                <div class="alert alert-success p-2">
                    <div>
                        <i class="fas fa-check-circle text-success me-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Pesan error umum login -->
            @if ($errors->any())
                <div class="alert alert-danger p-2">
                    @foreach ($errors->all() as $err)
                        <div>
                            <i class="fas fa-exclamation-circle text-danger me-2"></i>
                            {{ $err }}
                        </div>
                    @endforeach
                </div>
            @endif
        
            <div class="form-group">
                <label for="username">Username</label>
                <input type="username" id="username" name="username" placeholder="Enter your username" required>
            </div>

            <div class="form-group password-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <i class="fa fa-eye toggle-password" onclick="togglePassword(this)"></i>
                </div>
            </div>              

            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="signup-text">
            Don’t have an account? <a href="{{ route('register.show') }}">Sign Up</a>
        </div>

        <div class="footer-text">
            &copy; 2025 Informatics Labs Inventory
        </div>
    </div>

    <script>
        function togglePassword(el) {
            const input = el.previousElementSibling;
            const isPassword = input.type === "password";
        
            input.type = isPassword ? "text" : "password";
            el.classList.toggle("fa-eye");
            el.classList.toggle("fa-eye-slash");
        }
    </script>
</body>
</html>
