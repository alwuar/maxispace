<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ContactMedium;
use App\Enums\LeadStatus;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Registra un contacto con el prospecto y, si aplica, cambia su estado.
 * Cada registro queda en el historial del prospecto.
 */
class LeadActivityController extends Controller
{
    public function store(Request $request, Lead $lead): RedirectResponse
    {
        $datos = $request->validate([
            'estado' => ['required', Rule::enum(LeadStatus::class)],
            'asunto' => ['required', 'string', 'max:150'],
            'medio' => ['required', Rule::enum(ContactMedium::class)],
            'nota' => ['nullable', 'string', 'max:5000'],
        ], [], [
            'estado' => 'estado',
            'asunto' => 'asunto',
            'medio' => 'medio',
            'nota' => 'nota',
        ]);

        $nuevoEstado = LeadStatus::from($datos['estado']);

        DB::transaction(function () use ($lead, $datos, $nuevoEstado, $request) {
            $lead->activities()->create([
                'user_id' => $request->user()->id,
                'estado_anterior' => $lead->estado,
                'estado' => $nuevoEstado,
                'asunto' => trim($datos['asunto']),
                'medio' => $datos['medio'],
                'nota' => isset($datos['nota']) ? trim($datos['nota']) : null,
            ]);

            $lead->update([
                'estado' => $nuevoEstado,
                'ultimo_contacto_at' => now(),
            ]);
        });

        return redirect()->route('admin.leads.show', $lead)
            ->with('status', 'Contacto registrado en el historial.');
    }
}
