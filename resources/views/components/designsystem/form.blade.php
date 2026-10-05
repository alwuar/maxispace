@php
    $errores = $errors->getBag('contacto');
    $tamanos = \App\Models\Lead::TAMANOS;
@endphp

  <div class="form pb-5 scroll-animate" id="contacto">

        <div class="container ">
            <div class="titular">
                <h4>¿Necesitas espacio extra?</h4>
                <p>Déjanos tus datos y te ayudamos a elegir la minibodega perfecta para lo que quieres guardar. Respuesta rápida y sin compromiso.</p>
            </div>
            <form class="row g-3 formulario" method="POST" action="{{ route('leads.store') }}" novalidate>
                @csrf

                {{-- Campo trampa contra bots: no lo llenes --}}
                <div class="form-trampa" aria-hidden="true">
                    <label for="sitio_web">Sitio web</label>
                    <input type="text" name="sitio_web" id="sitio_web" tabindex="-1" autocomplete="off">
                </div>

                <div class="form_contenido">
                    @if ($errores->any())
                        <div class="alert alert-warning mb-0" role="alert">
                            Revisa los campos marcados para poder enviarte a WhatsApp.
                        </div>
                    @endif

                    <div class="col-md-12 pb-3">
                        <label for="nombre" class="visually-hidden">Nombre y apellido</label>
                        <input type="text" name="nombre" placeholder="NOMBRE Y APELLIDO" autocomplete="name" required
                               value="{{ old('nombre') }}"
                               class="form-control @if($errores->has('nombre')) is-invalid @endif" id="nombre">
                        @if($errores->has('nombre'))<div class="invalid-feedback">{{ $errores->first('nombre') }}</div>@endif
                    </div>
                    <div class="row">
                        <div class="col-md-6 pb-3">
                            <label for="telefono" class="visually-hidden">Teléfono</label>
                            <input type="tel" name="telefono" placeholder="TELÉFONO" autocomplete="tel" inputmode="tel" required
                                   value="{{ old('telefono') }}"
                                   class="form-control @if($errores->has('telefono')) is-invalid @endif" id="telefono">
                            @if($errores->has('telefono'))<div class="invalid-feedback">{{ $errores->first('telefono') }}</div>@endif
                        </div>
                        <div class="col-md-6 pb-3">
                            <label for="email" class="visually-hidden">Correo electrónico</label>
                            <input type="email" name="email" placeholder="EMAIL" autocomplete="email"
                                   value="{{ old('email') }}"
                                   class="form-control @if($errores->has('email')) is-invalid @endif" id="email">
                            @if($errores->has('email'))<div class="invalid-feedback">{{ $errores->first('email') }}</div>@endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pb-3">
                            <label for="que_almacenar" class="visually-hidden">¿Qué te gustaría almacenar?</label>
                            <input type="text" name="que_almacenar" placeholder="¿QUÉ TE GUSTARÍA ALMACENAR?"
                                   value="{{ old('que_almacenar') }}"
                                   class="form-control @if($errores->has('que_almacenar')) is-invalid @endif" id="que_almacenar">
                            @if($errores->has('que_almacenar'))<div class="invalid-feedback">{{ $errores->first('que_almacenar') }}</div>@endif
                        </div>
                        <div class="col-md-6 pb-3">
                            <label for="ciudad" class="visually-hidden">Ciudad</label>
                            <input type="text" name="ciudad" placeholder="CIUDAD" autocomplete="address-level2"
                                   value="{{ old('ciudad') }}"
                                   class="form-control @if($errores->has('ciudad')) is-invalid @endif" id="ciudad">
                            @if($errores->has('ciudad'))<div class="invalid-feedback">{{ $errores->first('ciudad') }}</div>@endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="tamano" class="visually-hidden">Tamaño de interés</label>
                        <select name="tamano" id="tamano" class="form-select @if($errores->has('tamano')) is-invalid @endif">
                            <option value="" @selected(! old('tamano'))>TAMAÑO DE INTERÉS (OPCIONAL)</option>
                            @foreach ($tamanos as $valor => $texto)
                                <option value="{{ $valor }}" @selected(old('tamano') === $valor)>{{ $texto }}</option>
                            @endforeach
                        </select>
                        @if($errores->has('tamano'))<div class="invalid-feedback">{{ $errores->first('tamano') }}</div>@endif
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input @if($errores->has('consentimiento')) is-invalid @endif"
                                   type="checkbox" name="consentimiento" value="1" id="consentimiento" required
                                   @checked(old('consentimiento'))>
                            <label class="form-check-label " for="consentimiento">
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
                    <div class="col-12 text-center mt-2">Recibirás comunicaciones por parte de nuestros asesores para
                        brindarte atención completa y personalizada, además de correos electrónicos con fines
                        informativos.
                    </div>
                </div>
            </form>
        </div>
    </div>

<script>
    // Los botones "Consulta disponibilidad" de cada bodega preseleccionan el tamaño
    (() => {
        const select = document.getElementById('tamano');
        if (!select) return;
        document.querySelectorAll('[data-tamano]').forEach(btn => {
            btn.addEventListener('click', () => {
                select.value = btn.dataset.tamano;
                setTimeout(() => document.getElementById('nombre')?.focus({ preventScroll: true }), 600);
            });
        });
    })();
</script>
