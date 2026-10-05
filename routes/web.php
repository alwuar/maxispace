<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\LeadActivityController;
use App\Http\Controllers\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

// Formulario de la landing → guarda prospecto → abre WhatsApp
Route::post('/contacto', [LeadController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('leads.store');

// ===== Panel administrativo =====
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'create'])->name('login');
        Route::post('login', [AuthController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AuthController::class, 'destroy'])->name('logout');

        Route::redirect('/', '/admin/prospectos')->name('home');

        Route::get('prospectos', [AdminLeadController::class, 'index'])->name('leads.index');
        Route::get('prospectos/exportar', [AdminLeadController::class, 'export'])->name('leads.export');
        Route::get('prospectos/{lead}', [AdminLeadController::class, 'show'])->name('leads.show');
        Route::post('prospectos/{lead}/actividad', [LeadActivityController::class, 'store'])->name('leads.activities.store');
    });
});
