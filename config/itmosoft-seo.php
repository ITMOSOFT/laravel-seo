<?php

return [
    'defaults' => [
        'title' => env('APP_NAME', 'Laravel'),
        'description' => null,
        'canonical' => null,
        'robots' => 'index,follow',
        'image' => null,
        'type' => 'website',
        'site_name' => env('APP_NAME', 'Laravel'),
        'locale' => 'en_US',
        'twitter_card' => 'summary_large_image',
    ],
];
