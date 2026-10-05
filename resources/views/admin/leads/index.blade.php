@php
    use App\Enums\LeadStatus;
    use App\Support\LeadFilters;
@endphp

<x-admin.layout title="Prospectos">

    <div class="page-head">
        <div>
            <h1>Prospectos</h1>
            <p class="sub">
                {{ LeadFilters::PERIODOS[$filtros->periodo] }}@if($filtros->rangoTexto()) · {{ $filtros->rangoTexto() }}@endif
                · {{ $leads->total() }} {{ $leads->total() === 1 ? 'resultado' : 'resultados' }}
            </p>
        </div>
        <a href="{{ route('admin.leads.export', $filtros->toQuery()) }}" class="btn btn-mx d-inline-flex align-items-center gap-2">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12m0 0-4-4m4 4 4-4M5 21h14"/></svg>
            Exportar a Excel
        </a>
    </div>

    {{-- ===== Filtros ===== --}}
    <form id="filtros" method="GET" action="{{ route('admin.leads.index') }}" class="mx-card mb-3">
        @if ($filtros->estado)
            <input type="hidden" name="estado" value="{{ $filtros->estado->value }}">
        @endif

        <div class="mx-card__title">Periodo</div>
        <div class="periodos mb-3" role="radiogroup" aria-label="Periodo">
            @foreach (LeadFilters::PERIODOS as $valor => $texto)
                <input type="radio" name="periodo" id="p-{{ $valor }}" value="{{ $valor }}" @checked($filtros->periodo === $valor)>
                <label for="p-{{ $valor }}">{{ $texto }}</label>
            @endforeach
        </div>

        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-6 col-lg-5 rango-fechas" @if($filtros->periodo !== 'personalizado') hidden @endif>
                <div class="row g-2">
                    <div class="col-6">
                        <label for="desde" class="form-label small text-secondary mb-1">Desde</label>
                        <input type="date" name="desde" id="desde" class="form-control" value="{{ $filtros->desde?->toDateString() }}">
                    </div>
                    <div class="col-6">
                        <label for="hasta" class="form-label small text-secondary mb-1">Hasta</label>
                        <input type="date" name="hasta" id="hasta" class="form-control" value="{{ $filtros->hasta?->toDateString() }}">
                    </div>
                </div>
            </div>
            <div class="col">
                <label for="q" class="form-label small text-secondary mb-1">Buscar</label>
                <input type="search" name="q" id="q" class="form-control" value="{{ $filtros->buscar }}"
                       placeholder="Nombre, teléfono, correo o folio">
            </div>
            <div class="col-12 col-sm-auto d-flex gap-2">
                <button type="submit" class="btn btn-mx flex-fill">Aplicar</button>
                @if ($filtros->toQuery())
                    <a href="{{ route('admin.leads.index') }}" class="btn btn-mx-outline flex-fill">Limpiar</a>
                @endif
            </div>
        </div>
    </form>

    {{-- ===== Contadores por estado (también filtran) ===== --}}
    <div class="estado-chips">
        <a href="{{ route('admin.leads.index', $filtros->toQuery(['estado' => null])) }}" @class(['is-active' => ! $filtros->estado])>
            <span class="chip-label">Todos</span>
            <span class="chip-num">{{ $totalPeriodo }}</span>
        </a>
        @foreach (LeadStatus::cases() as $estado)
            <a href="{{ route('admin.leads.index', $filtros->toQuery(['estado' => $estado->value])) }}"
               @class(['is-active' => $filtros->estado === $estado])>
                <span class="chip-label"><span class="dot {{ $estado->badge() }}"></span>{{ $estado->label() }}</span>
                <span class="chip-num">{{ $conteos[$estado->value] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    {{-- ===== Tabla ===== --}}
    @if ($leads->isEmpty())
        <div class="mx-card vacio">
            <h2>No hay prospectos con estos filtros</h2>
            <p class="mb-0">Prueba con otro periodo o limpia la búsqueda.</p>
        </div>
    @else
        <div class="tabla-wrap">
            <div class="table-responsive-md">
                <table class="table tabla-leads">
                    <thead>
                        <tr>
                            <th scope="col">Folio</th>
                            <th scope="col">Prospecto</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Tamaño</th>
                            <th scope="col">Registro</th>
                            <th scope="col">Último contacto</th>
                            <th scope="col">Estado</th>
                            <th scope="col"><span class="visually-hidden">Acciones</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leads as $lead)
                            <tr data-href="{{ route('admin.leads.show', $lead) }}">
                                <td data-label="Folio" class="folio">{{ $lead->folio() }}</td>
                                <td data-label="Prospecto" class="td-nombre">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.leads.show', $lead) }}" class="nombre">{{ $lead->nombre }}</a>
                                            @if ($lead->que_almacenar)
                                                <div class="sub text-truncate" style="max-width: 280px">{{ $lead->que_almacenar }}</div>
                                            @endif
                                        </div>
                                        <span class="badge-estado {{ $lead->estado->badge() }} d-md-none">{{ $lead->estado->label() }}</span>
                                    </div>
                                </td>
                                <td data-label="Teléfono">
                                    <a href="tel:{{ $lead->telefonoInternacional() }}" class="text-reset text-decoration-none">{{ $lead->telefono }}</a>
                                </td>
                                <td data-label="Tamaño">{{ $lead->tamanoLabel() ?? '—' }}</td>
                                <td data-label="Registro" class="fecha" title="{{ $lead->created_at->format('d/m/Y H:i') }}">
                                    {{ $lead->created_at->translatedFormat('j M Y, H:i') }}
                                </td>
                                <td data-label="Último contacto" class="fecha">
                                    @if ($lead->ultimo_contacto_at)
                                        {{ $lead->ultimo_contacto_at->diffForHumans() }}
                                    @else
                                        <span class="text-secondary">Sin contactar</span>
                                    @endif
                                </td>
                                <td data-label="Estado" class="d-none d-md-table-cell">
                                    <span class="badge-estado {{ $lead->estado->badge() }}">{{ $lead->estado->label() }}</span>
                                </td>
                                <td class="td-accion text-end">
                                    <a href="{{ route('admin.leads.show', $lead) }}" class="btn btn-sm btn-mx-outline">Ver</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $leads->onEachSide(1)->links() }}
        </div>
    @endif

</x-admin.layout>
