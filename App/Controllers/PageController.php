<?php

namespace Twork\App\Controllers;

use Twork\Controller\Controller;

/**
 * Class PageController
 * @package Twork\App\Controllers
 */
class PageController extends Controller
{
    public function data()
    {
        return [
            'title' => 'Page',
        ];
    }
}
