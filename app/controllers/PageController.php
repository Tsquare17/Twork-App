<?php

namespace Twork\App\Controllers;

use Twork\Theme;

class PageController extends Theme
{
    public $template;

    public function __construct()
    {
        $this->template = 'page';
    }

    public function data()
    {
        return [
            'title' => 'Page',
        ];
    }
}
