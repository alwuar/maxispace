@php
    $numero = preg_replace('/\D+/', '', (string) config('services.vandu.whatsapp'));
    $mensaje = 'Hola Vandu, quiero agregar más sucursales al panel de Maxispace.';
@endphp

<footer class="admin-footer">
    <div class="container-xl admin-footer__fila">
        <p class="admin-footer__credito">
            Desarrollado por
            <a href="https://agenciavandu.com" target="_blank" rel="noopener">agenciavandu.com</a>
        </p>

        @if ($numero)
            <a class="admin-footer__wa" href="https://wa.me/{{ $numero }}?text={{ rawurlencode($mensaje) }}"
               target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 11.5a8 8 0 0 1-11.8 7L4 20l1.5-4.1A8 8 0 1 1 20 11.5Z"/>
                </svg>
                Contactar por WhatsApp para agregar más sucursales
            </a>
        @endif
    </div>
</footer>
