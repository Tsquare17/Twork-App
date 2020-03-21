<?php

namespace Twork\App\Controllers;

use Twork\Theme;

/**
 * Class IndexController
 * @package Twork\App\Controllers
 */
class IndexController extends Theme
{
    /**
     * @var string The name of the blade template.
     */
    public $template;

    /**
     * IndexController constructor.
     */
    public function __construct()
    {
        $this->template = 'index';
    }
}
