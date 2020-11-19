<?php

namespace Twork\App\Controllers;

use Twork\Controller\Controller;

/**
 * Class IndexController
 * @package Twork\App\Controllers
 */
class IndexController extends Controller
{
    public function data()
    {
        return [
            'title' => 'Index',
        ];
    }
}
