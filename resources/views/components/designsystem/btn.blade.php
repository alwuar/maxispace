@props([
    'enlace' => '#',
    'boton' => 'Falta nombre del botón',
    'variante' => 'primary',
])

<a href="{{ $enlace }}" {{ $attributes->merge(['class' => "btn btn-{$variante}"]) }}>
    {{ $boton }}
</a>