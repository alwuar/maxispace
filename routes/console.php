<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * Crea (o actualiza) un administrador del panel.
 * Uso: php artisan admin:crear correo@maxispace.com.mx --nombre="Alvar"
 */
Artisan::command('admin:crear {email} {--nombre=Administrador}', function (string $email) {
    $validator = Validator::make(['email' => $email], ['email' => 'required|email']);
    if ($validator->fails()) {
        $this->error('El correo no es válido.');

        return 1;
    }

    $password = $this->secret('Contraseña (mínimo 8 caracteres)');
    if (! is_string($password) || strlen($password) < 8) {
        $this->error('La contraseña debe tener al menos 8 caracteres.');

        return 1;
    }

    $user = User::updateOrCreate(
        ['email' => strtolower($email)],
        ['name' => $this->option('nombre'), 'password' => $password, 'is_admin' => true],
    );

    $this->info("Administrador listo: {$user->email}");

    return 0;
})->purpose('Crear o actualizar un administrador del panel');
