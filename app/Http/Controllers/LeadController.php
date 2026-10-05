<?php

namespace App\Http\Controllers;

use App\Enums\LeadStatus;
use App\Models\Lead;
use App\Support\WhatsAppMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Recibe el formulario de la landing: guarda al prospecto y lo manda
 * a WhatsApp con el mensaje ya escrito.
 */
class LeadController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // Campo trampa: los humanos no lo ven, los bots lo llenan
        if ($request->filled('sitio_web')) {
            return redirect()->away(WhatsAppMessage::url('Hola, quiero información sobre las mini bodegas'));
        }

        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'min:3', 'max:120'],
            'telefono' => ['required', 'string', 'max:30', 'regex:/^[\d\s\-\+\(\)]{10,}$/'],
            'email' => ['nullable', 'email', 'max:150'],
            'tamano' => ['nullable', Rule::in(array_keys(Lead::TAMANOS))],
            'que_almacenar' => ['nullable', 'string', 'max:255'],
            'ciudad' => ['nullable', 'string', 'max:120'],
            'consentimiento' => ['accepted'],
        ], [
            'telefono.regex' => 'Escribe un teléfono válido de 10 dígitos.',
            'consentimiento.accepted' => 'Necesitamos tu autorización para poder contactarte.',
        ], [
            'nombre' => 'nombre',
            'telefono' => 'teléfono',
            'email' => 'correo',
            'tamano' => 'tamaño',
            'que_almacenar' => 'qué te gustaría almacenar',
            'ciudad' => 'ciudad',
        ]);

        if ($validator->fails()) {
            return redirect()->to(url('/').'#contacto')
                ->withErrors($validator, 'contacto')
                ->withInput();
        }

        $datos = $validator->validated();

        $lead = DB::transaction(function () use ($datos, $request) {
            $lead = Lead::create([
                'nombre' => trim($datos['nombre']),
                'telefono' => trim($datos['telefono']),
                'email' => $datos['email'] ?? null,
                'tamano' => $datos['tamano'] ?? null,
                'que_almacenar' => $datos['que_almacenar'] ?? null,
                'ciudad' => $datos['ciudad'] ?? null,
                'estado' => LeadStatus::Recibido,
                'origen' => 'formulario-web',
                'consentimiento_at' => now(),
                'ip' => $request->ip(),
            ]);

            $lead->activities()->create([
                'estado' => LeadStatus::Recibido,
                'asunto' => 'Solicitud recibida desde la web',
                'nota' => 'El prospecto llenó el formulario y fue enviado a WhatsApp.',
            ]);

            return $lead;
        });

        return redirect()->away(WhatsAppMessage::url(WhatsAppMessage::forLead($lead)));
    }
}
