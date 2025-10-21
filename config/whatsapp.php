<?php

return [
    'token'            => env('WHATSAPP_TOKEN'),
    'phone_number_id'  => env('WHATSAPP_PHONE_ID'),
    'graph_version'    => env('WHATSAPP_GRAPH_VERSION', 'v23.0'),
    'timeout'          => (int) env('WHATSAPP_TIMEOUT', 20),
    'connect_timeout'  => (int) env('WHATSAPP_CONNECT_TIMEOUT', 5),
];
