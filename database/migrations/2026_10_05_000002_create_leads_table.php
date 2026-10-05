<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Prospectos que llenan el formulario de la landing
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('telefono', 30);
            $table->string('email')->nullable();
            $table->string('tamano', 20)->nullable();        // 1.5x3, 3x3, 6x3, no-se
            $table->string('que_almacenar')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('estado', 20)->default('recibido')->index();
            $table->string('origen', 40)->default('formulario-web');
            $table->timestamp('consentimiento_at')->nullable();
            $table->timestamp('ultimo_contacto_at')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();

            $table->index('created_at');
        });

        // Historial de contacto / cambios de estado de cada prospecto
        Schema::create('lead_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('estado_anterior', 20)->nullable();
            $table->string('estado', 20);
            $table->string('asunto');
            $table->string('medio', 20)->nullable();          // whatsapp, llamada, correo
            $table->text('nota')->nullable();
            $table->timestamps();

            $table->index(['lead_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_activities');
        Schema::dropIfExists('leads');
    }
};
