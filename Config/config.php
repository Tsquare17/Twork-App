<?php

use Twork\App\Controllers\FrontPageController;
use Twork\App\Controllers\IndexController;
use Twork\App\Controllers\PageController;
use Twork\App\Forms\ContactForm;
use Twork\App\Posts\CustomPost;
use Twork\App\Admin\MenuPages\Example;

return [
    'templates' => [
        'front-page' => FrontPageController::class,
        'index' => IndexController::class,
        'page' => PageController::class,
    ],
    'custom_posts' => [
        CustomPost::class,
    ],
    'dashboard_menus' => [
        Example::class,
    ],
    'forms' => [
        ContactForm::class,
    ]
];
