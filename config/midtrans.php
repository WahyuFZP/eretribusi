<?php

return [
    /* Midtrans server key (keep secret) */
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),

    /* Midtrans client key (used by frontend Snap SDK) */
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),

    /* Environment flags */
    'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => (bool) env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => (bool) env('MIDTRANS_IS_3DS', true),
];
