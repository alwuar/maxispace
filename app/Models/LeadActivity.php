<?php

namespace App\Models;

use App\Enums\ContactMedium;
use App\Enums\LeadStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['lead_id', 'user_id', 'estado_anterior', 'estado', 'asunto', 'medio', 'nota'])]
class LeadActivity extends Model
{
    protected function casts(): array
    {
        return [
            'estado' => LeadStatus::class,
            'estado_anterior' => LeadStatus::class,
            'medio' => ContactMedium::class,
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cambioEstado(): bool
    {
        return $this->estado_anterior !== null && $this->estado_anterior !== $this->estado;
    }
}
