<?php

return [
    'idocs.categories' => [
        'index' => 'idocs::categories.list',
        'create' => 'idocs::categories.create',
        'edit' => 'idocs::categories.edit',
        'destroy' => 'idocs::categories.destroy',
    ],
    'idocs.docs' => [
        'index' => 'idocs::doc.list',
        'create' => 'idocs::doc.create',
        'edit' => 'idocs::doc.edit',
        'destroy' => 'idocs::doc.destroy',
    ]

];
