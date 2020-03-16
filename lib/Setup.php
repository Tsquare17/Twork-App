<?php

namespace Twork;

/**
 * Class Setup
 * @package Twork
 */
class Setup
{
    /**
     * Setup constructor.
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueGlobalScripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueGlobalStyles']);

        $this->run();
    }

    /**
     * Enqueue global scripts.
     */
    public function enqueueGlobalScripts()
    {
        wp_enqueue_script('twork-js', TWORK_JS_URL . '/twork.min.js', ['jquery'], TWORK_VERSION, true);
    }

    /**
     * Enqueue global styles.
     */
    public function enqueueGlobalStyles()
    {
        wp_enqueue_style('twork-stylesheet', get_stylesheet_uri(), null, TWORK_VERSION);
        wp_enqueue_style('twork-css', TWORK_CSS_URL . '/twork.min.css', null, TWORK_VERSION);
    }

    /**
     * Run Theme, Run!
     */
    protected function run()
    {
        new Theme();
    }
}
