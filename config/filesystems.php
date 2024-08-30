<?php

return [
    'default' => 'local',
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => '/DATA',
        ],
        'fonts' => [
            'driver' => 'local',
            'root' => storage_path('fonts'),
        ],
    ],
];
