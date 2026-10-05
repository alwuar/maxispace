<header class="header">
    {{-- Objetos flotantes (decorativos) --}}
    <div class="flotantes" aria-hidden="true">
        <div class="flotante flotante--caja">
            <div class="flotante__inner"><img src="{{ asset('img/caja.png') }}" alt=""></div>
        </div>
        <div class="flotante flotante--contenedor">
            <div class="flotante__inner"><img src="{{ asset('img/box-plastico.png') }}" alt=""></div>
        </div>
        <div class="flotante flotante--dron">
            <div class="flotante__inner"><img src="{{ asset('img/drone.png') }}" alt=""></div>
        </div>
        <div class="flotante flotante--silla">
            <div class="flotante__inner"><img src="{{ asset('img/silla.png') }}" alt=""></div>
        </div>
    </div>
    <div class="container">
        <div class="titular">
            <x-designsystem.bread bread="Renta de mini bodegas" class="bread small" />
            <h1>Tu espacio <span class="extra">extra</span> en Mérida</h1>
            <p class="descripcion">Mini bodegas de 4 a 18 m² en Mérida, con vigilancia 24/7 y contrato desde 1 mes.</p>
            <x-designsystem.btn enlace="https://agenciavandu.com/" boton="Cotiza tu bodega" class="btn btn-maxidark" />
              
        </div>
    </div>
</header>

