@props([
    'telefono' => '529993515866',
    'mensaje'  => 'Hola, quiero información sobre las mini bodegas',
])

<a class="wa-flotante"
   href="https://wa.me/{{ $telefono }}?text={{ urlencode($mensaje) }}"
   target="_blank" rel="noopener"
   aria-label="Contactar por WhatsApp">
    {{-- Ícono de chat (puedes cambiarlo por el ícono oficial de WhatsApp) --}}
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M20 11.5a8 8 0 0 1-11.8 7L4 20l1.5-4.1A8 8 0 1 1 20 11.5Z"/>
        <path d="M9.2 8.6c.3-.6.6-.6.9-.6h.5c.2 0 .4.1.5.4l.7 1.6c.1.3 0 .5-.1.7l-.5.6c-.1.2-.1.4 0 .6.5.9 1.3 1.7 2.2 2.2.2.1.4.1.6 0l.6-.5c.2-.1.4-.2.7-.1l1.6.7c.3.1.4.3.4.5v.5c0 .3 0 .6-.6.9-.6.3-1.6.5-3-.2a8.3 8.3 0 0 1-3.6-3.6c-.6-1.4-.4-2.4-.1-3Z"/>
    </svg>
</a>
