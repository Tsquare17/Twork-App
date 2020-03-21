<?php

namespace Twork\App\Controllers;

use Twork\Theme;

/**
 * Class PageController
 * @package Twork\App\Controllers
 */
class PageController extends Theme
{
    /**
     * @var string The name of the blade template.
     */
    public $template;

    /**
     * PageController constructor.
     */
    public function __construct()
    {
        $this->template = 'page';
    }

    /**
     * Return an array of variables to pass to the template.
     *
     * @return array
     */
    public function data()
    {
        return [
            'title' => 'Page',
        ];
    }
}
