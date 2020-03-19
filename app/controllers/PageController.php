<?php

namespace Twork\App\Controllers;

use Twork\Theme;

class PageController extends Theme
{
    protected $template;

    public function __construct()
    {
        $this->template = 'page';
    }
}
