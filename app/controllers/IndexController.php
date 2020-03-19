<?php

namespace Twork\App\Controllers;

use Twork\Theme;

class IndexController extends Theme
{
    protected $template;

    public function __construct()
    {
        $this->template = 'index';
    }
}
