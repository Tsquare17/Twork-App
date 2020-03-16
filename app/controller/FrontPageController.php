<?php

namespace Twork\App\Controller;

use Twork\Theme;

class FrontPageController extends Theme
{
    public function __construct()
    {
        $this->registerTemplate('front-page', 'homepage');
    }

    public function data()
    {
        return [
            'title' => 'T-Work',
        ];
    }

    public function footerScripts()
    {
        return [
            'front-page-script-handle' => $this->script('/test.js', ['jquery'])
        ];
    }

    public function styles()
    {
        return [
            'front-page-style-handle' => $this->style('/test.css')
        ];
    }
}
