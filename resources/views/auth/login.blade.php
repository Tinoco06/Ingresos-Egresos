<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Iniciar Sesión</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --olive: #556B2F;
            --olive-dark: #3B4A1F;
            --olive-light: #6B8E23;
            --olive-pale: #f4f7ec;
            --gold: #BFA14A;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2d3a1a 0%, #556B2F 50%, #3B4A1F 100%);
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(ellipse at 30% 20%, rgba(107, 142, 35, 0.15) 0%, transparent 50%),
                        radial-gradient(ellipse at 70% 80%, rgba(191, 161, 74, 0.1) 0%, transparent 50%);
            animation: float 15s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-20px, -20px) rotate(2deg); }
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .login-brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-brand .icon-circle {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--olive-light), var(--olive));
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .login-brand .icon-circle i {
            font-size: 2rem;
            color: #fff;
        }

        .login-brand h1 {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .login-brand p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
        }

        .login-card {
            background: #fff;
            border-radius: 16px;
            padding: 35px 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .login-card h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #555;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1rem;
            transition: color 0.3s;
        }

        .input-icon-wrapper input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: 'Nunito', sans-serif;
            transition: all 0.3s ease;
            outline: none;
            background: #fafafa;
        }

        .input-icon-wrapper input:focus {
            border-color: var(--olive);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(85, 107, 47, 0.15);
        }

        .input-icon-wrapper input:focus + i,
        .input-icon-wrapper input:focus ~ i {
            color: var(--olive);
        }

        .input-icon-wrapper input.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 5px;
            display: block;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .remember-check {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--olive);
            cursor: pointer;
        }

        .remember-check label {
            font-size: 0.85rem;
            color: #666;
            cursor: pointer;
            margin: 0;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--olive-light), var(--olive));
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Nunito', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, var(--olive), var(--olive-dark));
            box-shadow: 0 6px 20px rgba(85, 107, 47, 0.4);
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .login-footer p {
            color: #999;
            font-size: 0.8rem;
        }

        .login-footer i {
            color: var(--gold);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-brand">
            <div class="icon-circle">
                <i class="bi bi-cash-coin"></i>
            </div>
            <h1>{{ config('app.name') }}</h1>
        </div>

        <div class="login-card">
            <h2>Iniciar Sesión</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <div class="input-icon-wrapper">
                        <input id="email" type="email" name="email" class="{{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="tu@correo.com">
                        <i class="bi bi-envelope"></i>
                    </div>
                    @error('email')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-icon-wrapper">
                        <input id="password" type="password" name="password" class="{{ $errors->has('password') ? 'is-invalid' : '' }}" required autocomplete="current-password" placeholder="••••••••">
                        <i class="bi bi-lock"></i>
                    </div>
                    @error('password')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-options">
                    <div class="remember-check">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Recordarme</label>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar
                </button>
            </form>

        </div>
    </div>
</body>
</html>
