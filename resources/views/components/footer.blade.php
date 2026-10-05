<footer class="footer">
    <div class="container">
        <div class="footer__grid">

            {{-- Columna 1: logo y frase --}}
            <div class="footer__marca">
                <a href="/" class="footer__logo" aria-label="Maxispace, ir al inicio">
                    <img src="{{ asset('img/maxispace-logo-blanco.svg') }}" alt="Maxispace" width="141" height="45">
                </a>
                <p class="footer__frase">Guarda lo tuyo, seguro y cerca</p>
            </div>

            {{-- Columna 2: navegación --}}
            <nav class="footer__nav" aria-label="Navegación del pie de página">
                <h2 class="footer__titulo">Navegación</h2>
                <ul>
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#tipos-de-almacenamiento">Tipos de almacenamiento</a></li>
                    <li><a href="#minibodegas">Minibodegas</a></li>
                    <li><a href="#ubicacion">Ubicación</a></li>
                    <li><a href="#contacto">Contáctanos</a></li>
                </ul>
            </nav>

            {{-- Columna 3: dirección y contacto --}}
            <div class="footer__contacto">
                <h2 class="footer__titulo">Visítanos</h2>
                <address>
                    <strong class="footer__sucursal">Maxispace Mérida II — Los Héroes</strong>
                    Calle 149, Manzana AV, entre 4A y AB, Col. Francisco Villa, Los Héroes, C.P. 97306, Mérida, Yucatán
                </address>
                <ul class="footer__datos">
                    <li><a href="tel:+529993515866">999 351 5866</a></li>
                    <li><a href="mailto:merida@maxispace.com.mx">merida@maxispace.com.mx</a></li>
                </ul>
            </div>

        </div>
    </div>
</footer>
