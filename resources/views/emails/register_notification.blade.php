<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso</title>
    <!-- Incluir Tailwind CSS desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-blue-500">
    <div class="max-w-xl mx-auto py-8 px-4 bg-white rounded-lg shadow-lg border-2 border-blue-500">
        <div class="bg-blue-500 text-white px-6 py-4 rounded-t-lg">
            <h2 class="text-3xl font-bold mb-2">¡Bienvenido a nuestra aplicación!</h2>
            <p class="text-lg">¡Hola <strong>{{ $user->name}}</strong>!</p>
        </div>
        <div class="p-6">
            <p class="mb-4 text-lg text-gray-700">Te has registrado exitosamente en nuestra aplicación. ¡Gracias por unirte a nosotros!</p>
            <p class="mb-4 text-lg text-gray-700">Si tienes alguna pregunta o necesitas asistencia, no dudes en ponerte en contacto con nosotros.</p>
            <p class="mb-4 text-lg text-gray-700">Saludos cordiales,</p>
            <p class="mb-4 text-lg text-gray-700">El equipo de <strong>{{ config('app.name') }}</strong></p>
        </div>
    </div>
</body>
</html>
