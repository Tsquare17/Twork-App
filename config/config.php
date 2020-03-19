<?php

use Twork\App\Controllers\FrontPageController;
use Twork\App\Controllers\IndexController;
use Twork\App\Controllers\PageController;

return [
    'templates' => [
        'front-page' => FrontPageController::class,
        'index' => IndexController::class,
        'page' => PageController::class,
    ]
];
