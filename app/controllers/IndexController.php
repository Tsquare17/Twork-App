<?php

namespace Twork\App\Controllers;

use Twork\Theme;

class IndexController extends Theme
{
    public $template;

    public function __construct()
    {
        $this->template = 'index';
    }
}
