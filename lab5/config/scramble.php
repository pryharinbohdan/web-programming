<?php

return [
    'api_path' => 'api',
    'api_domain' => null,
    'info' => [
        'version' => '1.0.0',
        'description' => 'REST API каталогу комп’ютерної техніки (Варіант №9). Забезпечує CRUD операції для товарів та категорій.',
    ],
    'renderer' => 'elements',
    'renderers' => [
        'elements' => [
            'hideTryIt' => false,
        ],
    ],
    'ui' => [
        'title' => 'Каталог техніки — Документація API',
        'theme' => 'light',
        'hide_try_it' => false,
    ],
    'middleware' => [
        'web',
    ],
];
