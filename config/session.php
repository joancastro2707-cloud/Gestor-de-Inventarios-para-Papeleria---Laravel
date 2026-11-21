<?php

use Illuminate\Support\Str;

return [

    'driver' => 'file',

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    'encrypt' => env('SESSION_ENCRYPT', false),

    'files' => storage_path('framework/sessions'),

    'connection' => env('SESSION_CONNECTION'),

    'table' => env('SESSION_TABLE', 'sessions'),

    'store' => env('SESSION_STORE'),

    'lottery' => [2, 100],

    'cookie' => 'papeleria_session',

    'path' => '/',

    'domain' => null,

    'secure' => false,

    'http_only' => true,

    'same_site' => 'lax',

    'partitioned' => false,

];