<?php

namespace Twork\App\Controller;

use Twork\Theme;

class PageController extends Theme
{
    public function __construct()
    {
        $this->registerTemplate('page', 'page');
    }
}
