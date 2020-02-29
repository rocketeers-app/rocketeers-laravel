<?php

return [
    'api_token' => env('ROCKETEERS_API_TOKEN'),
    'notifications' => [
        'discord' => [
            'webhook_url' => env('ROCKETEERS_DISCORD_WEBHOOK_URL'),
        ],
    ],
];
