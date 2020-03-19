<?php

namespace Twork\App\Controller;

use Twork\Theme;

class PageController extends Theme
{
    protected $template;

    public function __construct()
    {
        $this->template = 'page';
    }
}
