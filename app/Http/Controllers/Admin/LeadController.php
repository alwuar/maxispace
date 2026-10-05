<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeadStatus;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Support\LeadFilters;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = LeadFilters::fromRequest($request);

        $leads = $filtros->query()
            ->with('latestActivity')
            ->latest()
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        // Conteo por estado dentro del periodo/búsqueda actual
        $conteos = $filtros->applyBase(Lead::query())
            ->selectRaw('estado, count(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        return view('admin.leads.index', [
            'leads' => $leads,
            'filtros' => $filtros,
            'conteos' => $conteos,
            'totalPeriodo' => $conteos->sum(),
        ]);
    }

    public function show(Lead $lead): View
    {
        $lead->load(['activities.user']);

        return view('admin.leads.show', [
            'lead' => $lead,
            'estados' => LeadStatus::cases(),
        ]);
    }

    /** Exporta a CSV (abre directo en Excel) con los mismos filtros de la tabla */
    public function export(Request $request): StreamedResponse
    {
        $filtros = LeadFilters::fromRequest($request);

        $nombre = 'prospectos-maxispace-'.$filtros->periodo
            .($filtros->estado ? '-'.$filtros->estado->value : '')
            .'-'.now()->format('Y-m-d').'.csv';

        $query = $filtros->query()->with('latestActivity')->latest()->latest('id');

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM para que Excel respete acentos

            fputcsv($out, [
                'Folio', 'Fecha de registro', 'Nombre', 'Teléfono', 'Correo',
                'Tamaño de interés', 'Qué quiere almacenar', 'Ciudad', 'Origen', 'Estado',
                'Último contacto', 'Último asunto', 'Última nota',
            ]);

            $query->chunk(500, function ($leads) use ($out) {
                foreach ($leads as $lead) {
                    $ultima = $lead->latestActivity;

                    fputcsv($out, [
                        $lead->folio(),
                        $lead->created_at->format('Y-m-d H:i'),
                        $lead->nombre,
                        $lead->telefono,
                        $lead->email,
                        $lead->tamanoLabel(),
                        $lead->que_almacenar,
                        $lead->ciudad,
                        $lead->origenLabel(),
                        $lead->estado->label(),
                        $lead->ultimo_contacto_at?->format('Y-m-d H:i'),
                        $ultima?->asunto,
                        $ultima?->nota,
                    ]);
                }
            });

            fclose($out);
        }, $nombre, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
