<?php

namespace App\Enums;

enum LeadStatus: string
{
    case Recibido = 'recibido';
    case Contactado = 'contactado';
    case Perdido = 'perdido';
    case Ganado = 'ganado';

    public function label(): string
    {
        return match ($this) {
            self::Recibido => 'Recibido',
            self::Contactado => 'Contactado',
            self::Perdido => 'Perdido',
            self::Ganado => 'Ganado',
        };
    }

    /** Clase CSS del badge en el panel */
    public function badge(): string
    {
        return 'estado--'.$this->value;
    }
}
