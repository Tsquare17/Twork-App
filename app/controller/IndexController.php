<?php

namespace Twork\App\Controller;

use Twork\Theme;

class IndexController extends Theme
{
    public function __construct()
    {
        $this->registerTemplate('index', 'index');
    }
}
