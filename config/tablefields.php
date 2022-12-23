<?php

return [
    'events'    => [
        'name',
        'description',
        'thumbnail' => [
            'label' => 'Thumbnail',
            'type'  => 'file'
        ],
    ],
    'forms' => [
        'name',
        'label',
        'type' => [
            'label' => 'Type',
            'type'  => 'options:text|number|options|file|textarea|date|datetime-local|wa|foto|nama'
        ],
        'type_param' => [
            'label' => 'Type Param (Optional)',
            'type'  => 'text'
        ]
    ]
];