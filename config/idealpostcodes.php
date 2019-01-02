<?php
return [
    // The API key for ideal postcodes.
    'key' => env('IDEALPOSTCODES_KEY', 'AXXASDAS'),
    // The cache store used to hold postcode entries.
    'store' => env('IDEALPOSTCODES_STORE', 'database'),
    // How long to hold postcode lookups in cache.
    'cache_expires' => env('IDEALPOSTCODES_CACHE_EXPIRES', '+1 year'),
];
