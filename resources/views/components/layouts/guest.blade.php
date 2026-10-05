@props([
   
    'title'       => 'Mini bodegas en renta en Mérida',
    'description' => 'Renta de mini bodegas de 4 a 18 m² en Mérida, Yucatán. Vigilancia 24/7, acceso seguro y contratos desde 1 mes. Cotiza hoy por WhatsApp.',
    'image'       => asset('img/og-maxispace.jpg'), // 1200 x 630 px
])

@php
    $tituloCompleto = "{$title} | Maxispace";
    $urlActual = url()->current();

    $sucursales = [
        [
            'nombre'    => 'Maxispace Mérida I',
            'calle'     => 'Calle 21 No. 435 por 26 y 28, Col. Ciudad Industrial',
            'cp'        => '97288',
            'mapa'      => 'https://www.google.com/maps?cid=7800786366828222121',
        ],
        [
            'nombre'    => 'Maxispace Mérida II — Los Héroes',
            'calle'     => 'Calle 149, Manzana AV, entre 4A y AB, Col. Francisco Villa, Los Héroes',
            'cp'        => '97306',
            'mapa'      => 'https://www.google.com/maps?cid=13635556387118320332',
        ],
    ];

    $schema = [
        '@context' => 'https://schema.org',
        '@graph'   => array_merge(
            [
                [
                    '@type' => 'Organization',
                    '@id'   => url('/') . '#organizacion',
                    'name'  => 'Maxispace',
                    'url'   => url('/'),
                    'logo'  => asset('img/maxispace-logo.svg'),
                    'email' => 'merida@maxispace.com.mx',
                    'telephone' => '+52 999 351 5866',
                ],
                [
                    '@type' => 'WebSite',
                    '@id'   => url('/') . '#sitio',
                    'name'  => 'Maxispace',
                    'url'   => url('/'),
                    'inLanguage' => 'es-MX',
                    'publisher'  => ['@id' => url('/') . '#organizacion'],
                ],
            ],
            array_map(fn ($s, $i) => [
                '@type'       => 'SelfStorage',
                '@id'         => url('/') . '#sucursal-' . ($i + 1),
                'name'        => $s['nombre'],
                'description' => 'Renta de mini bodegas de 4 a 18 m² con vigilancia 24/7 y contratos desde 1 mes.',
                'url'         => url('/'),
                'image'       => $image,
                'telephone'   => '+52 999 351 5866',
                'email'       => 'merida@maxispace.com.mx',
                'hasMap'      => $s['mapa'],
                'parentOrganization' => ['@id' => url('/') . '#organizacion'],
                'address' => [
                    '@type'           => 'PostalAddress',
                    'streetAddress'   => $s['calle'],
                    'addressLocality' => 'Mérida',
                    'addressRegion'   => 'Yucatán',
                    'postalCode'      => $s['cp'],
                    'addressCountry'  => 'MX',
                ],
                'areaServed' => ['@type' => 'City', 'name' => 'Mérida, Yucatán'],
                // Agrega aquí tu horario real cuando lo tengas, por ejemplo:
                'openingHours' => ['Mo-Fr 08:00-18:00', 'Sa 09:00-14:00'],
            ], $sucursales, array_keys($sucursales))
        ),
    ];
@endphp

<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- ===== Básicas: lo que Google muestra en los resultados ===== --}}
    <title>{{ $tituloCompleto }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $urlActual }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">

    {{-- ===== Al compartir en WhatsApp, Facebook, LinkedIn ===== --}}
    <meta property="og:type" content="website">
    <meta property="og:locale" content="es_MX">
    <meta property="og:site_name" content="Maxispace">
    <meta property="og:title" content="{{ $tituloCompleto }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $urlActual }}">
    <meta property="og:image" content="{{ $image }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Mini bodegas Maxispace en Mérida, Yucatán">

    {{-- ===== Al compartir en X (Twitter) ===== --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $tituloCompleto }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $image }}">

    {{-- ===== Íconos y color del navegador en celular ===== --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('img/favicon.svg') }}" type="image/svg+xml">
    <meta name="theme-color" content="#0066B5">

    {{-- ===== Datos estructurados (negocio local) ===== --}}
    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>

    @stack('estilos')
</head>

<body>
    <x-whatsapp-flotante />
    <x-nav />

    <main>
        {{ $slot }}
    </main>

    <x-footer />

    {{-- Formulario emergente de los botones de WhatsApp --}}
    <x-contacto.modal />
</body>

</html>
