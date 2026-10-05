{{--
    Ventana emergente que abren "Hablar por WhatsApp" (menú) y el botón flotante.
    Pide los mismos datos que la sección #contacto antes de mandar a WhatsApp,
    así todos los prospectos quedan registrados en el panel.
--}}
@php
    $origenEnviado = old('origen');
    $abrirConErrores = $origenEnviado && $origenEnviado !== 'formulario-web' && $errors->getBag('contacto')->any();
@endphp

<dialog id="modal-contacto" class="modal-contacto" aria-labelledby="modal-contacto-titulo" data-lenis-prevent
        @if($abrirConErrores) data-abrir-al-cargar @endif>
    <div class="modal-contacto__caja">
        <button type="button" class="modal-contacto__cerrar" data-cerrar-contacto aria-label="Cerrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <path d="M6 6l12 12M18 6 6 18"/>
            </svg>
        </button>

        <div class="modal-contacto__encabezado">
            <h2 id="modal-contacto-titulo">Habla con un asesor</h2>
            <p>Llena tus datos y da clic en <strong>“Enviar por WhatsApp”</strong> para hablar con un asesor.</p>
        </div>

        <form class="formulario" method="POST" action="{{ route('leads.store') }}" novalidate>
            <x-contacto.campos modo="modal" />
        </form>
    </div>
</dialog>

<script>
    (() => {
        const modal = document.getElementById('modal-contacto');
        if (!modal || typeof modal.showModal !== 'function') return; // navegadores viejos: el botón abre WhatsApp directo

        const campoOrigen = modal.querySelector('[data-campo-origen]');

        const abrir = (origen) => {
            if (origen && campoOrigen) campoOrigen.value = origen;
            modal.showModal();
            document.documentElement.classList.add('modal-abierto');
            // En celular no se abre el teclado de golpe; en escritorio sí enfoca el nombre
            if (window.matchMedia('(min-width: 768px)').matches) {
                modal.querySelector('[data-campo-nombre]')?.focus();
            }
        };

        const cerrar = () => modal.close();

        document.addEventListener('click', (e) => {
            const boton = e.target.closest('[data-abrir-contacto]');
            if (!boton) return;
            e.preventDefault();
            abrir(boton.dataset.origen);
        });

        modal.querySelectorAll('[data-cerrar-contacto]').forEach(b => b.addEventListener('click', cerrar));

        // Clic en el fondo oscuro (fuera de la caja) también cierra
        modal.addEventListener('click', (e) => { if (e.target === modal) cerrar(); });

        modal.addEventListener('close', () => document.documentElement.classList.remove('modal-abierto'));

        // Si el envío desde la ventana tuvo errores, se vuelve a abrir para corregirlos
        if (modal.hasAttribute('data-abrir-al-cargar')) abrir();
    })();
</script>
