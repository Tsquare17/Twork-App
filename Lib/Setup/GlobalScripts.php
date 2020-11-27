<?php

namespace Twork\Theme\Setup;

/**
 * Class GlobalScripts
 * @package Twork\Theme
 */
class GlobalScripts
{
    public function __construct()
    {
        wp_enqueue_script('twork-js', TWORK_JS_URL . '/twork.min.js', ['jquery'], TWORK_VERSION, true);
    }
}
