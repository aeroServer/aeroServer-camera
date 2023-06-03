<?php

return [
    'default' => 'local',
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => $_SERVER['HOME'].'/.config/aeroServer-camera',
        ],
        'fonts' => [
            'driver' => 'local',
            'root' => storage_path('fonts'),
        ],
    ],
];