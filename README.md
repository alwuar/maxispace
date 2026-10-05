# Maxispace — landing + panel de prospectos

## Qué incluye

- **Formulario de la landing** (`/#contacto`): guarda al prospecto y lo manda a WhatsApp con un mensaje preformateado (folio, nombre, teléfono, tamaño, qué quiere guardar). Los botones "Consulta disponibilidad" preseleccionan el tamaño.
- **Ventana emergente**: "Hablar por WhatsApp" (menú) y el botón flotante abren el mismo formulario antes de mandar a WhatsApp, así todos los prospectos quedan registrados. El panel muestra de qué botón llegó cada uno.
- **Panel administrativo** (`/admin`): acceso solo para administradores.
  - Lista de prospectos con filtros por **hoy, semana, mes, año** o **fechas personalizadas**, por estado y búsqueda.
  - **Exportar a Excel** (CSV) con el filtro seleccionado.
  - Detalle de cada prospecto: cambiar estado (**Recibido, Contactado, Perdido, Ganado**), asunto, medio (**WhatsApp / llamada / correo**) y nota.
  - **Historial de contacto** dentro de cada prospecto.
  - Todo responsivo: en celular la tabla se convierte en tarjetas.

## Puesta en marcha

```bash
composer install
cp .env.example .env      # ajusta DB_*, APP_URL y WHATSAPP_NUMERO
php artisan key:generate
php artisan migrate
npm install && npm run build

# Crear tu usuario administrador (te pide la contraseña)
php artisan admin:crear tu-correo@maxispace.com.mx --nombre="Tu nombre"
```

Luego entra a `https://tu-dominio/admin`.

Variables nuevas en `.env`:

| Variable | Para qué |
|---|---|
| `WHATSAPP_NUMERO` | Número que recibe los prospectos (lada + número, sin + ni espacios). Ej. `529993515866` |
| `APP_LOCALE` | Ponlo en `es` para que los mensajes de error salgan en español |
| `APP_TIMEZONE` | Zona horaria para los filtros de fecha. Por defecto `America/Merida` |

Pruebas: `php artisan test` (ver `tests/Feature/LeadsTest.php`).

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
# maxispace
