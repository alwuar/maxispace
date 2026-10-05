<?php

// Mensajes de validación en español (solo las reglas que usa el sitio y el panel).
// Si necesitas más, agrégalas aquí con la misma forma.
return [
    'accepted' => 'Debes aceptar :attribute.',
    'array' => ':Attribute debe ser una lista.',
    'boolean' => ':Attribute debe ser verdadero o falso.',
    'date' => ':Attribute no es una fecha válida.',
    'email' => 'Escribe un :attribute válido.',
    'enum' => 'El valor de :attribute no es válido.',
    'in' => 'El valor de :attribute no es válido.',
    'integer' => ':Attribute debe ser un número entero.',
    'max' => [
        'numeric' => ':Attribute no puede ser mayor que :max.',
        'string' => ':Attribute no puede tener más de :max caracteres.',
        'array' => ':Attribute no puede tener más de :max elementos.',
        'file' => ':Attribute no puede pesar más de :max kilobytes.',
    ],
    'min' => [
        'numeric' => ':Attribute debe ser al menos :min.',
        'string' => ':Attribute debe tener al menos :min caracteres.',
        'array' => ':Attribute debe tener al menos :min elementos.',
        'file' => ':Attribute debe pesar al menos :min kilobytes.',
    ],
    'numeric' => ':Attribute debe ser un número.',
    'regex' => 'El formato de :attribute no es válido.',
    'required' => 'Escribe tu :attribute.',
    'string' => ':Attribute debe ser texto.',
    'unique' => 'Ese :attribute ya está registrado.',

    'custom' => [
        'estado' => ['required' => 'Elige un estado.'],
        'medio' => ['required' => 'Elige el medio de contacto.'],
        'asunto' => ['required' => 'Escribe el asunto del contacto.'],
        'password' => ['required' => 'Escribe tu contraseña.'],
        'email' => ['required' => 'Escribe tu correo.'],
    ],

    'attributes' => [],
];
