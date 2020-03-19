<?php

namespace Twork\App\Controller;

use Twork\Theme;

class IndexController extends Theme
{
    protected $template;

    public function __construct()
    {
        $this->template = 'index';
    }
}
