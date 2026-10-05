<nav class="navbar-cristal container" aria-label="Navegación principal">
    <div class="navbar-cristal__barra">

        {{-- Columna 1: logo --}}
        <a href="/" class="navbar-cristal__logo" aria-label="Maxispace, ir al inicio">
            <img src="{{ asset('img/maxispace-logo-blanco.svg') }}" alt="Maxispace" width="141" height="45">
        </a>

        {{-- Hamburguesa (solo se ve en celular y tablet) --}}
        <button class="navbar-cristal__toggle" type="button"
                aria-expanded="false" aria-controls="navbar-menu" aria-label="Abrir menú">
            <span></span><span></span><span></span>
        </button>

        <div class="navbar-cristal__menu" id="navbar-menu">
            {{-- Columna 2: enlaces --}}
            <ul class="navbar-cristal__enlaces">
                <li><a href="#inicio" aria-current="page">Inicio</a></li>
                <li><a href="#tipos-de-almacenamiento">Tipos de almacenamiento</a></li>
                <li><a href="#minibodegas">Minibodegas</a></li>
                <li><a href="#ubicacion">Ubicación</a></li>
                <li><a href="#contacto">Contáctanos</a></li>
            </ul>

            {{-- Columna 3: botón --}}
            <a class="navbar-cristal__cta"
               href="https://wa.me/529993515866?text={{ urlencode('Hola, quiero información sobre las mini bodegas') }}"
               target="_blank" rel="noopener"
               data-abrir-contacto data-origen="boton-menu"
               aria-haspopup="dialog" aria-controls="modal-contacto">
                Hablar por WhatsApp
            </a>
        </div>
    </div>
</nav>

<script>
    // Abre/cierra el menú en celular
    (() => {
        const toggle = document.querySelector('.navbar-cristal__toggle');
        const menu = document.getElementById('navbar-menu');
        if (!toggle || !menu) return;

        const setOpen = (open) => {
            menu.classList.toggle('is-open', open);
            toggle.setAttribute('aria-expanded', open);
            toggle.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
        };

        toggle.addEventListener('click', () => setOpen(!menu.classList.contains('is-open')));
        menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => setOpen(false)));
        document.addEventListener('keydown', e => { if (e.key === 'Escape') setOpen(false); });
    })();
</script>
