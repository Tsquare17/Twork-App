<?php

namespace Twork\Theme\Setup;

/**
 * Class GlobalStyles
 * @package Twork\Theme
 */
class GlobalStyles
{
    public function __construct()
    {
        wp_enqueue_style('twork-stylesheet', get_stylesheet_uri(), null, TWORK_VERSION);
        wp_enqueue_style('twork-css', TWORK_CSS_URL . '/twork.min.css', null, TWORK_VERSION);
    }
}
