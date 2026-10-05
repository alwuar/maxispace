@php
    use App\Enums\ContactMedium;
    $estadoActual = old('estado', $lead->estado->value);
    $medioActual = old('medio', ContactMedium::WhatsApp->value);
@endphp

<x-admin.layout :title="$lead->nombre">

    <a href="{{ url()->previous() !== url()->current() && str_contains(url()->previous(), '/admin/prospectos') ? url()->previous() : route('admin.leads.index') }}"
       class="d-inline-flex align-items-center gap-1 text-decoration-none small mb-2">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
        Prospectos
    </a>

    <div class="page-head">
        <div class="min-w-0">
            <h1 class="text-break">{{ $lead->nombre }}</h1>
            <p class="sub">{{ $lead->folio() }} · Registrado {{ $lead->created_at->translatedFormat('j \d\e F Y, H:i') }}</p>
        </div>
        <span class="badge-estado {{ $lead->estado->badge() }} fs-6">{{ $lead->estado->label() }}</span>
    </div>

    <div class="row g-3">
        {{-- ===== Columna izquierda: datos + registrar contacto ===== --}}
        <div class="col-12 col-lg-5">
            <section class="mx-card">
                <div class="mx-card__title">Contactar</div>
                <div class="acciones-rapidas mb-4">
                    <a href="https://wa.me/{{ $lead->telefonoInternacional() }}" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 11.5a8 8 0 0 1-11.8 7L4 20l1.5-4.1A8 8 0 1 1 20 11.5Z"/></svg>
                        WhatsApp
                    </a>
                    <a href="tel:+{{ $lead->telefonoInternacional() }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/></svg>
                        Llamar
                    </a>
                    <a href="{{ $lead->email ? 'mailto:'.$lead->email : '#' }}" @class(['disabled' => ! $lead->email]) @if(! $lead->email) aria-disabled="true" @endif>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                        Correo
                    </a>
                </div>

                <div class="mx-card__title">Datos del prospecto</div>
                <dl class="datos-lista">
                    <div><dt>Teléfono</dt><dd>{{ $lead->telefono }}</dd></div>
                    <div><dt>Correo</dt><dd>{{ $lead->email ?? '—' }}</dd></div>
                    <div><dt>Tamaño de interés</dt><dd>{{ $lead->tamanoLabel() ?? '—' }}</dd></div>
                    <div><dt>Ciudad</dt><dd>{{ $lead->ciudad ?? '—' }}</dd></div>
                    <div class="grid-full" style="grid-column: 1 / -1"><dt>Qué quiere almacenar</dt><dd>{{ $lead->que_almacenar ?? '—' }}</dd></div>
                    <div><dt>Origen</dt><dd>{{ $lead->origenLabel() }}</dd></div>
                    <div><dt>Último contacto</dt><dd>{{ $lead->ultimo_contacto_at?->translatedFormat('j M Y, H:i') ?? 'Sin contactar' }}</dd></div>
                    <div><dt>Autorizó contacto</dt><dd>{{ $lead->consentimiento_at ? 'Sí, '.$lead->consentimiento_at->translatedFormat('j M Y') : 'No' }}</dd></div>
                </dl>
            </section>

            <section class="mx-card" id="registrar">
                <div class="mx-card__title">Registrar contacto / cambiar estado</div>

                <form method="POST" action="{{ route('admin.leads.activities.store', $lead) }}" novalidate>
                    @csrf

                    <fieldset class="mb-3">
                        <legend class="form-label fs-6 mb-2">Estado</legend>
                        <div @class(['opciones', 'is-invalid' => $errors->has('estado')])>
                            @foreach ($estados as $estado)
                                <input type="radio" name="estado" id="estado-{{ $estado->value }}" value="{{ $estado->value }}" @checked($estadoActual === $estado->value)>
                                <label for="estado-{{ $estado->value }}"><span class="dot {{ $estado->badge() }}"></span>{{ $estado->label() }}</label>
                            @endforeach
                        </div>
                        @error('estado')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </fieldset>

                    <div class="mb-3">
                        <label for="asunto" class="form-label">Asunto</label>
                        <input type="text" name="asunto" id="asunto" list="asuntos" value="{{ old('asunto') }}" maxlength="150" required
                               placeholder="Ej. Primer contacto"
                               class="form-control @error('asunto') is-invalid @enderror">
                        <datalist id="asuntos">
                            <option value="Primer contacto">
                            <option value="Seguimiento">
                            <option value="Envío de cotización">
                            <option value="Visita a sucursal">
                            <option value="Firma de contrato">
                            <option value="Sin respuesta">
                        </datalist>
                        @error('asunto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <fieldset class="mb-3">
                        <legend class="form-label fs-6 mb-2">Medio</legend>
                        <div @class(['opciones', 'is-invalid' => $errors->has('medio')])>
                            @foreach (ContactMedium::cases() as $medio)
                                <input type="radio" name="medio" id="medio-{{ $medio->value }}" value="{{ $medio->value }}" @checked($medioActual === $medio->value)>
                                <label for="medio-{{ $medio->value }}">{{ $medio->label() }}</label>
                            @endforeach
                        </div>
                        @error('medio')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </fieldset>

                    <div class="mb-3">
                        <label for="nota" class="form-label">Nota</label>
                        <textarea name="nota" id="nota" rows="4" maxlength="5000"
                                  placeholder="¿Qué pasó en el contacto? Ej. Le interesa la bodega 3x3, pidió cotización por 3 meses."
                                  class="form-control @error('nota') is-invalid @enderror">{{ old('nota') }}</textarea>
                        @error('nota')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-mx w-100">Guardar en historial</button>
                </form>
            </section>
        </div>

        {{-- ===== Columna derecha: historial ===== --}}
        <div class="col-12 col-lg-7">
            <section class="mx-card">
                <div class="mx-card__title">Historial de contacto ({{ $lead->activities->count() }})</div>

                @if ($lead->activities->isEmpty())
                    <p class="text-secondary mb-0">Todavía no hay registros.</p>
                @else
                    <ol class="historial">
                        @foreach ($lead->activities as $act)
                            <li>
                                <span class="dot {{ $act->estado->badge() }}"></span>
                                <div class="h-top">
                                    <span class="h-asunto">{{ $act->asunto }}</span>
                                    @if ($act->medio)
                                        <span class="badge-medio">{{ $act->medio->label() }}</span>
                                    @endif
                                </div>
                                <div class="h-meta">
                                    <time datetime="{{ $act->created_at->toIso8601String() }}">{{ $act->created_at->translatedFormat('j M Y, H:i') }}</time>
                                    · {{ $act->user?->name ?? 'Sistema' }}
                                </div>
                                @if ($act->cambioEstado())
                                    <div class="h-cambio">
                                        Estado: {{ $act->estado_anterior->label() }} → <strong>{{ $act->estado->label() }}</strong>
                                    </div>
                                @endif
                                @if ($act->nota)
                                    <div class="h-nota">{{ $act->nota }}</div>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                @endif
            </section>
        </div>
    </div>

</x-admin.layout>
