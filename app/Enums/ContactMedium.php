<?php

namespace App\Enums;

enum ContactMedium: string
{
    case WhatsApp = 'whatsapp';
    case Llamada = 'llamada';
    case Correo = 'correo';

    public function label(): string
    {
        return match ($this) {
            self::WhatsApp => 'WhatsApp',
            self::Llamada => 'Llamada',
            self::Correo => 'Correo',
        };
    }
}
