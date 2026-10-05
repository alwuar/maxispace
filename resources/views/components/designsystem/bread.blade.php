@props(['bread' => 'Pendiente escribir informacion'])

<span {{ $attributes->merge(['class' => 'bread-crumb']) }}>
  <span><img src="{{ asset('img/elipse.svg') }}" width="14" alt="circulo vector"></span>  {{ $bread }}
</span>