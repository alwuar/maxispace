@push('estilos')
    @vite(['resources/scss/app.scss', 'resources/scss/welcome.scss', 'resources/js/app.js', 'resources/js/header-flotantes.js','resources/js/scroll-reveal.js'])
@endpush

<x-layouts.guest title="Mini bodegas en renta">

    <x-header />

    <section class="valor-agregado scroll-animate pt-5 pb-5" id="tipos-de-almacenamiento">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <x-designsystem.bread bread="¿Para quien es Maxispace?" class="bread small" />
                    <h3 class="pt-4 pb-4">Un espacio para cada necesidad</h3>
                    <ul class="p-0 valor__lista_point">
                        <li>
                            <span>
                                <img src="{{ asset('/img/check.svg') }}" width="20" alt="checklist">
                            </span>
                            <strong style="padding-left: 10px">Para tu hogar</strong> <br>
                            Guarda muebles, cajas y pertenencias durante una mudanza,
                            remodelación o simplemente para liberar espacio.
                        </li>
                        <li>
                            <span>
                                <img src="{{ asset('/img/check.svg') }}" width="20" alt="checklist">
                            </span><strong style="padding-left: 10px">Para emprendedores</strong><br>
                            Almacena tu inventario y mercancía sin pagar la renta de un local completo.
                        </li>
                        <li>
                            <span>
                                <img src="{{ asset('/img/check.svg') }}" width="20" alt="checklist">
                            </span><strong style="padding-left: 10px">Para comercios y empresas</strong><br>
                            Guarda documentos, herramientas y equipo con acceso controlado y seguro.
                        </li>
                    </ul>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 text-center">
                    <img src="{{ asset('img/bodega-valor.png') }}" class="img-fluid" alt="Mini bodega">
                </div>
            </div>
        </div>
    </section>

    <x-products />
    
    <x-designsystem.cta />
    <x-designsystem.mapa />

    <x-designsystem.form />

</x-layouts.guest>
