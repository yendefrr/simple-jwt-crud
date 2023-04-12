<?php

return [
    'mysql' => [
        'host' => 'db',
        'dbname' => 'test',
        'user' => 'root',
        'password' => 'root',
    ],
    'jwt' => [
        'secret' => 'secret',
        'algorithm' => 'HS256',
        'expire' => 3600,
    ],
];