<?php

namespace Twork\App\Controllers;

use Twork\Controller\Controller;

/**
 * Class PageController
 * @package Twork\App\Controllers
 */
class PageController extends Controller
{
    /**
     * @var string The name of the blade template.
     */
    public $template = 'page';

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
