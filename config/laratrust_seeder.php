<?php

return [
    'role_structure' => [
        'superadministrator' => [
            'users' => 'c,r,u,d',
            'acl' => 'c,r,u,d',
            'settings' => 'r,u',
            'airline' => 'c,r,u,d',
            'fleet' => 'c,r,u,d',
            'schedule' => 'c,r,u,d',
            'pireps' => 'r,u',
            'profile' => 'r,u',
        ],
        'administrator' => [
            'users' => 'c,r,u,d',
            'airline' => 'c,r,u,d',
            'fleet' => 'c,r,u,d',
            'schedule' => 'c,r,u,d',
            'pireps' => 'r,u',
            'profile' => 'r,u',
        ],
        'user' => [
            'profile' => 'r,u',
        ],
    ],
    'permission_structure' => [],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
