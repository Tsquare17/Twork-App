<?php

namespace Twork\App\Controllers;

use Twork\App\Queries\CustomPost;
use Twork\Theme;

/**
 * Class FrontPageController
 * @package Twork\App\Controllers
 */
class FrontPageController extends Theme
{
    /**
     * @var string The name of the blade template.
     */
    public $template;

    /**
     * FrontPageController constructor.
     */
    public function __construct()
    {
        $this->template = 'homepage';
    }

    /**
     * Return an array of variables to pass to the template.
     *
     * @return array
     */
    public function data()
    {
        $customPost = new CustomPost();

        return [
            'title' => 'T-Work',
            'customPosts' => $customPost->get(),
        ];
    }

    /**
     * Scripts to be enqueued in the footer.
     *
     * @return array
     */
    public function footerScripts()
    {
        return [
            'front-page-script-handle' => $this->script('/test.min.js', ['jquery'])
        ];
    }

    /**
     * Styles to be enqueued.
     *
     * @return array
     */
    public function styles()
    {
        return [
            'front-page-style-handle' => $this->style('/test.min.css')
        ];
    }
}
