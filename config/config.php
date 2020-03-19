<?php

use Twork\App\Controller\FrontPageController;
use Twork\App\Controller\IndexController;
use Twork\App\Controller\PageController;

return [
    'templates' => [
        'front-page' => FrontPageController::class,
        'index' => IndexController::class,
        'page' => PageController::class,
    ]
];
