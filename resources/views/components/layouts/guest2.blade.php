<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ $tilte ?? 'Mini bodegas en renta' }} - Maxispace </title>
    @stack('estilos')
</head>

<body>
    <x-whatsapp-flotante />
    <x-nav />

    <body>
        {{ $slot }}
    </body>

    <x-footer />

    {{-- Formulario emergente de los botones de WhatsApp --}}
    <x-contacto.modal />
</body>

</html>
