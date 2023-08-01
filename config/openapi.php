<?php

use IrealWorlds\OpenApi\Models\Document\ServerDto;

return [
    /*
     * App information that should be added to the OpenApi file.
     */
    "app_name" => "Your Laravel App API",
    "app_description" => "API documentation for your Laravel application.",
    "app_version" => "1.0.0",

    /*
     * Methods ignored when extracting route documentation.
     * These should be the name of the http verbs that should be ignored in lowercase.
     */
    "ignored_methods" => [
        "options",
        "head",
    ],

    /*
     * The servers that should be registered.
     */
    'servers' => [
        new ServerDto(
            config('app.url') ?? "127.0.0.1:8000",
            "Laravel built-in development server"
        )
    ]
];