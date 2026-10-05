<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ $tilte ?? 'Mini bodegas en renta' }} - Maxispace </title>
    @stack('estilos')
</head>

<body>
    <nav />

    <body>
        {{ $slot }}
    </body>

    <footer />
</body>

</html>
