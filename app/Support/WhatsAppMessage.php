<?php

namespace App\Support;

use App\Models\Lead;

/**
 * Arma el mensaje preformateado que el prospecto envía por WhatsApp
 * después de llenar el formulario.
 */
class WhatsAppMessage
{
    public static function forLead(Lead $lead): string
    {
        $lineas = [
            '¡Hola Maxispace! 👋 Quiero información sobre minibodegas.',
            '',
            '*Folio:* '.$lead->folio(),
            '*Nombre:* '.$lead->nombre,
            '*Teléfono:* '.$lead->telefono,
        ];

        if ($lead->email) {
            $lineas[] = '*Correo:* '.$lead->email;
        }
        if ($lead->tamano) {
            $lineas[] = '*Tamaño de interés:* '.$lead->tamanoLabel();
        }
        if ($lead->que_almacenar) {
            $lineas[] = '*Qué quiero guardar:* '.$lead->que_almacenar;
        }
        if ($lead->ciudad) {
            $lineas[] = '*Ciudad:* '.$lead->ciudad;
        }

        $lineas[] = '';
        $lineas[] = '¿Me ayudan a elegir la bodega ideal?';

        return implode("\n", $lineas);
    }

    public static function url(string $mensaje, ?string $numero = null): string
    {
        $numero = preg_replace('/\D+/', '', $numero ?? (string) config('services.whatsapp.numero'));

        return 'https://wa.me/'.$numero.'?text='.rawurlencode($mensaje);
    }
}
