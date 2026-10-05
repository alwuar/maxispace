{{--
    Campos del formulario de contacto. Se usa en la sección #contacto
    y en la ventana emergente de los botones de WhatsApp.
    - $modo: 'seccion' o 'modal' (cambia ids y dónde se muestran los errores)
--}}
@props(['modo' => 'seccion'])

@php
    $p = $modo === 'modal' ? 'm-' : '';               // prefijo de ids para no repetirlos
    $origenEnviado = old('origen');
    $esModalEnviado = $origenEnviado && $origenEnviado !== 'formulario-web';

    // Los errores y valores previos solo se muestran en el formulario que se envió
    $esEste = $modo === 'modal' ? $esModalEnviado : ! $esModalEnviado;
    $errores = $esEste ? $errors->getBag('contacto') : new \Illuminate\Support\MessageBag;
    $val = fn (string $campo) => $esEste ? old($campo) : null;

    $tamanos = \App\Models\Lead::TAMANOS;
    $origenDefecto = $modo === 'modal' ? ($esModalEnviado ? $origenEnviado : 'boton-flotante') : 'formulario-web';
@endphp

@csrf
<input type="hidden" name="origen" value="{{ $origenDefecto }}" data-campo-origen>

{{-- Campo trampa contra bots: no lo llenes --}}
<div class="form-trampa" aria-hidden="true">
    <label for="{{ $p }}sitio_web">Sitio web</label>
    <input type="text" name="sitio_web" id="{{ $p }}sitio_web" tabindex="-1" autocomplete="off">
</div>

<div class="form_contenido">
    @if ($errores->any())
        <div class="alert alert-warning mb-0" role="alert">
            Revisa los campos marcados para poder enviarte a WhatsApp.
        </div>
    @endif

    <div class="col-md-12 pb-3">
        <label for="{{ $p }}nombre" class="visually-hidden">Nombre y apellido</label>
        <input type="text" name="nombre" placeholder="NOMBRE Y APELLIDO" autocomplete="name" required
               value="{{ $val('nombre') }}" data-campo-nombre
               class="form-control @if($errores->has('nombre')) is-invalid @endif" id="{{ $p }}nombre">
        @if($errores->has('nombre'))<div class="invalid-feedback">{{ $errores->first('nombre') }}</div>@endif
    </div>
    <div class="row">
        <div class="col-md-6 pb-3">
            <label for="{{ $p }}telefono" class="visually-hidden">Teléfono</label>
            <input type="tel" name="telefono" placeholder="TELÉFONO" autocomplete="tel" inputmode="tel" required
                   value="{{ $val('telefono') }}"
                   class="form-control @if($errores->has('telefono')) is-invalid @endif" id="{{ $p }}telefono">
            @if($errores->has('telefono'))<div class="invalid-feedback">{{ $errores->first('telefono') }}</div>@endif
        </div>
        <div class="col-md-6 pb-3">
            <label for="{{ $p }}email" class="visually-hidden">Correo electrónico</label>
            <input type="email" name="email" placeholder="EMAIL" autocomplete="email"
                   value="{{ $val('email') }}"
                   class="form-control @if($errores->has('email')) is-invalid @endif" id="{{ $p }}email">
            @if($errores->has('email'))<div class="invalid-feedback">{{ $errores->first('email') }}</div>@endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 pb-3">
            <label for="{{ $p }}que_almacenar" class="visually-hidden">¿Qué te gustaría almacenar?</label>
            <input type="text" name="que_almacenar" placeholder="¿QUÉ TE GUSTARÍA ALMACENAR?"
                   value="{{ $val('que_almacenar') }}"
                   class="form-control @if($errores->has('que_almacenar')) is-invalid @endif" id="{{ $p }}que_almacenar">
            @if($errores->has('que_almacenar'))<div class="invalid-feedback">{{ $errores->first('que_almacenar') }}</div>@endif
        </div>
        <div class="col-md-6 pb-3">
            <label for="{{ $p }}ciudad" class="visually-hidden">Ciudad</label>
            <input type="text" name="ciudad" placeholder="CIUDAD" autocomplete="address-level2"
                   value="{{ $val('ciudad') }}"
                   class="form-control @if($errores->has('ciudad')) is-invalid @endif" id="{{ $p }}ciudad">
            @if($errores->has('ciudad'))<div class="invalid-feedback">{{ $errores->first('ciudad') }}</div>@endif
        </div>
    </div>
    <div class="col-md-12">
        <label for="{{ $p }}tamano" class="visually-hidden">Tamaño de interés</label>
        <select name="tamano" id="{{ $p }}tamano" data-campo-tamano
                class="form-select @if($errores->has('tamano')) is-invalid @endif">
            <option value="" @selected(! $val('tamano'))>TAMAÑO DE INTERÉS (OPCIONAL)</option>
            @foreach ($tamanos as $valor => $texto)
                <option value="{{ $valor }}" @selected($val('tamano') === $valor)>{{ $texto }}</option>
            @endforeach
        </select>
        @if($errores->has('tamano'))<div class="invalid-feedback">{{ $errores->first('tamano') }}</div>@endif
    </div>
    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input @if($errores->has('consentimiento')) is-invalid @endif"
                   type="checkbox" name="consentimiento" value="1" id="{{ $p }}consentimiento" required
                   @checked($val('consentimiento'))>
            <label class="form-check-label " for="{{ $p }}consentimiento">
                Autorizo a Maxispace a contactarme por WhatsApp, llamada o correo electrónico para
                brindarme información sobre sus servicios.
            </label>
            @if($errores->has('consentimiento'))<div class="invalid-feedback">{{ $errores->first('consentimiento') }}</div>@endif
        </div>
    </div>
    <div class="col-12 mt-2" style="display: flex; flex-direction: row; justify-content: center; align-items: center">
        <button type="submit" class="btn btn-maxiblue arrow btn-block">
            ENVIAR POR WHATSAPP <span>
                <img src="{{ asset('/img/Arrow.svg') }}" width="18" alt="">
            </span>
        </button>
    </div>
    @if ($modo === 'seccion')
        <div class="col-12 text-center mt-2">Recibirás comunicaciones por parte de nuestros asesores para
            brindarte atención completa y personalizada, además de correos electrónicos con fines
            informativos.
        </div>
    @endif
</div>
