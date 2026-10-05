<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Iniciar sesión | Panel Maxispace</title>
    <link rel="icon" href="{{ asset('img/favicon.svg') }}" type="image/svg+xml">
    <meta name="theme-color" content="#293239">
    @vite(['resources/scss/admin.scss'])
</head>
<body class="admin-login">
    <main class="login-card">
        <img src="{{ asset('img/maxispace-logo.svg') }}" alt="Maxispace" class="logo" width="125" height="40">
        <h1>Panel de administración</h1>
        <p class="sub mb-4">Acceso exclusivo para administradores.</p>

        <form method="POST" action="{{ route('admin.login.store') }}" novalidate>
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Correo</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" autocomplete="username" required autofocus
                       class="form-control @error('email') is-invalid @enderror">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" id="password" autocomplete="current-password" required
                       class="form-control @error('password') is-invalid @enderror">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                <label class="form-check-label" for="remember">Mantener sesión iniciada</label>
            </div>

            <button type="submit" class="btn btn-mx w-100 py-2">Entrar</button>
        </form>
    </main>

    <p class="login-credito">
        Desarrollado por <a href="https://agenciavandu.com" target="_blank" rel="noopener">agenciavandu.com</a>
    </p>
</body>
</html>
