<?php

namespace App\Support;

use App\Enums\LeadStatus;
use App\Models\Lead;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Filtros del listado de prospectos. Se usan igual en la tabla
 * y en la exportación, para que el archivo salga con lo que se ve.
 */
class LeadFilters
{
    public const PERIODOS = [
        'todos' => 'Todo',
        'hoy' => 'Hoy',
        'semana' => 'Esta semana',
        'mes' => 'Este mes',
        'anio' => 'Este año',
        'personalizado' => 'Personalizado',
    ];

    public string $periodo = 'todos';

    public ?CarbonImmutable $desde = null;

    public ?CarbonImmutable $hasta = null;

    public ?LeadStatus $estado = null;

    public ?string $buscar = null;

    public static function fromRequest(Request $request): self
    {
        $f = new self;

        $periodo = $request->string('periodo')->toString();
        $f->periodo = array_key_exists($periodo, self::PERIODOS) ? $periodo : 'todos';
        $f->estado = LeadStatus::tryFrom($request->string('estado')->toString());
        $f->buscar = $request->filled('q') ? trim($request->string('q')->toString()) : null;

        $ahora = CarbonImmutable::now();

        [$f->desde, $f->hasta] = match ($f->periodo) {
            'hoy' => [$ahora->startOfDay(), $ahora->endOfDay()],
            'semana' => [$ahora->startOfWeek(CarbonImmutable::MONDAY), $ahora->endOfWeek(CarbonImmutable::SUNDAY)],
            'mes' => [$ahora->startOfMonth(), $ahora->endOfMonth()],
            'anio' => [$ahora->startOfYear(), $ahora->endOfYear()],
            'personalizado' => self::rango($request->input('desde'), $request->input('hasta')),
            default => [null, null],
        };

        return $f;
    }

    /** @return array{0: ?CarbonImmutable, 1: ?CarbonImmutable} */
    private static function rango(mixed $desde, mixed $hasta): array
    {
        $d = self::fecha($desde)?->startOfDay();
        $h = self::fecha($hasta)?->endOfDay();

        // Si las pusieron al revés, se acomodan solas
        if ($d && $h && $d->greaterThan($h)) {
            [$d, $h] = [$h->startOfDay(), $d->endOfDay()];
        }

        return [$d, $h];
    }

    private static function fecha(mixed $valor): ?CarbonImmutable
    {
        if (! is_string($valor) || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
            return null;
        }

        try {
            $fecha = CarbonImmutable::createFromFormat('Y-m-d', $valor);

            // Rechaza fechas imposibles como 2026-02-31
            return $fecha && $fecha->format('Y-m-d') === $valor ? $fecha : null;
        } catch (\Throwable) {
            return null;
        }
    }

    /** Aplica periodo y búsqueda (sin estado, para poder contar por estado) */
    public function applyBase(Builder $query): Builder
    {
        return $query
            ->when($this->desde, fn (Builder $q) => $q->where('created_at', '>=', $this->desde))
            ->when($this->hasta, fn (Builder $q) => $q->where('created_at', '<=', $this->hasta))
            ->search($this->buscar);
    }

    public function apply(Builder $query): Builder
    {
        return $this->applyBase($query)
            ->when($this->estado, fn (Builder $q) => $q->where('estado', $this->estado->value));
    }

    public function query(): Builder
    {
        return $this->apply(Lead::query());
    }

    /** Texto legible del rango, p. ej. "1 oct 2026 – 31 oct 2026" */
    public function rangoTexto(): ?string
    {
        if (! $this->desde && ! $this->hasta) {
            return null;
        }

        $fmt = fn (CarbonImmutable $d) => $d->locale('es')->translatedFormat('j M Y');

        return match (true) {
            $this->desde && $this->hasta => $fmt($this->desde).' – '.$fmt($this->hasta),
            (bool) $this->desde => 'Desde '.$fmt($this->desde),
            default => 'Hasta '.$fmt($this->hasta),
        };
    }

    /** Parámetros para mantener los filtros en enlaces (paginación, exportar) */
    public function toQuery(array $extra = []): array
    {
        return array_filter([
            'periodo' => $this->periodo !== 'todos' ? $this->periodo : null,
            'desde' => $this->periodo === 'personalizado' ? $this->desde?->toDateString() : null,
            'hasta' => $this->periodo === 'personalizado' ? $this->hasta?->toDateString() : null,
            'estado' => $this->estado?->value,
            'q' => $this->buscar,
            ...$extra,
        ], fn ($v) => $v !== null && $v !== '');
    }
}
