<?php

namespace Twork\App\Controller;

use Twork\Theme;

class FrontPageController extends Theme
{
    protected $template;

    public function __construct()
    {
        $this->template = 'homepage';
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
            'front-page-script-handle' => $this->script('/test.min.js', ['jquery'])
        ];
    }

    public function styles()
    {
        return [
            'front-page-style-handle' => $this->style('/test.min.css')
        ];
    }
}
