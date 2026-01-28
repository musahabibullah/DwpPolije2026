<?php

return [
    'secret' => env('JWT_SECRET', 'default_secret_key'),
    'expiration' => env('JWT_EXPIRATION', 3600),
];
