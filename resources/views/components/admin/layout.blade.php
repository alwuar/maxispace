@props(['title' => 'Panel'])

<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title }} | Panel Maxispace</title>
    <link rel="icon" href="{{ asset('img/favicon.svg') }}" type="image/svg+xml">
    <meta name="theme-color" content="#293239">
    @vite(['resources/scss/admin.scss', 'resources/js/admin.js'])
</head>
<body class="admin">
    <nav class="navbar navbar-expand-md navbar-dark admin-nav">
        <div class="container-xl">
            <a class="navbar-brand" href="{{ route('admin.leads.index') }}">
                <img src="{{ asset('img/maxispace-logo-blanco.svg') }}" alt="Maxispace" width="100" height="32">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#adminMenu"
                    aria-controls="adminMenu" aria-expanded="false" aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminMenu">
                <ul class="navbar-nav me-auto mt-2 mt-md-0 ms-md-3">
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('admin.leads.*')) active @endif"
                           href="{{ route('admin.leads.index') }}">Prospectos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}" target="_blank" rel="noopener">Ver sitio</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3 py-2 py-md-0">
                    <span class="admin-user text-truncate">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-salir">Cerrar sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="admin-main">
        <div class="container-xl">
            @if (session('status'))
                <div class="alert alert-success d-flex align-items-center gap-2" role="status">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>

    <x-admin.footer />
</body>
</html>
