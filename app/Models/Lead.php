<?php

namespace App\Models;

use App\Enums\LeadStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'nombre', 'telefono', 'email', 'tamano', 'que_almacenar', 'ciudad',
    'estado', 'origen', 'consentimiento_at', 'ultimo_contacto_at', 'ip',
])]
class Lead extends Model
{
    /** Tamaños que se ofrecen en el formulario */
    public const TAMANOS = [
        'no-se' => 'Aún no lo sé',
        '1.5x3' => 'Bodega 1.5m x 3m',
        '3x3' => 'Bodega 3m x 3m',
        '6x3' => 'Bodega 6m x 3m',
    ];

    /** De dónde llegó el prospecto */
    public const ORIGENES = [
        'formulario-web' => 'Formulario de contacto',
        'boton-menu' => 'Botón "Hablar por WhatsApp"',
        'boton-flotante' => 'Botón flotante de WhatsApp',
    ];

    protected function casts(): array
    {
        return [
            'estado' => LeadStatus::class,
            'consentimiento_at' => 'datetime',
            'ultimo_contacto_at' => 'datetime',
        ];
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class)->latest()->latest('id');
    }

    public function latestActivity(): HasOne
    {
        return $this->hasOne(LeadActivity::class)->latestOfMany();
    }

    public function folio(): string
    {
        return 'MX-'.str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    public function tamanoLabel(): ?string
    {
        return $this->tamano ? (self::TAMANOS[$this->tamano] ?? $this->tamano) : null;
    }

    public function origenLabel(): string
    {
        return self::ORIGENES[$this->origen] ?? $this->origen;
    }

    /** Teléfono solo con dígitos y lada de México para enlaces wa.me / tel: */
    public function telefonoInternacional(): string
    {
        $digitos = preg_replace('/\D+/', '', $this->telefono);

        return strlen($digitos) === 10 ? '52'.$digitos : $digitos;
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! filled($term)) {
            return $query;
        }

        $like = '%'.str_replace(['%', '_'], ['\%', '\_'], trim($term)).'%';

        return $query->where(function (Builder $q) use ($like, $term) {
            $q->where('nombre', 'like', $like)
                ->orWhere('telefono', 'like', $like)
                ->orWhere('email', 'like', $like)
                ->orWhere('que_almacenar', 'like', $like);

            // Permite buscar por folio: MX-00012 o 12
            $id = (int) preg_replace('/\D+/', '', $term);
            if ($id > 0) {
                $q->orWhere('id', $id);
            }
        });
    }
}
