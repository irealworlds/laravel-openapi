<?php

return [
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
    ]
];