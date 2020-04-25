<?php

namespace Twork\Template;

use Twork\Controller;

/**
 * Class Interceptor
 * @package Twork\Template
 */
class Interceptor
{
    /**
     * @var array Template controller map.
     */
    protected $templates;

    /**
     * @var string Controller to use.
     */
    protected $controller;

    /**
     * @var array Scripts ready to be enqueued.
     */
    protected $scripts;

    /**
     * @var array Styles ready to be enqueued.
     */
    protected $styles;

    /**
     * @var array Scripts from the controller to be enqueued in the footer.
     */
    protected $controllerFooterScripts;

    /**
     * @var array Scripts from the controller to be enqueued in the header.
     */
    protected $controllerHeaderScripts;

    /**
     * @var array Styles from the controller.
     */
    protected $controllerStyles;

    /**
     * Interceptor constructor.
     *
     * @param $templates
     */
    public function __construct($templates)
    {
        $this->templates      = $templates;

        add_filter('template_include', [$this, 'templateInterceptor']);
    }

    /**
     * template_include filter.
     *
     * @param $template
     *
     * @return mixed
     */
    public function templateInterceptor($template)
    {
        $array         = explode('/', $template);
        $templateFile  = end($array);
        $tworkTemplate = str_replace('.php', '', $templateFile);

        $this->controller = $this->templates[$tworkTemplate] ?? null;

        return isset($this->templates[$tworkTemplate]) ? $this->runController() : $template;
    }

    /**
     * Collect and enqueue assets, and render the template.
     */
    public function runController(): void
    {
        /**
         * @var Controller $controller
         */
        $controller = new $this->controller();

        $this->controllerFooterScripts = $controller->footerScripts();
        $this->controllerHeaderScripts = $controller->headerScripts();
        $this->controllerStyles        = $controller->styles();

        $this->processScripts();
        $this->processStyles();

        add_action('wp_enqueue_scripts', [$this, 'enqueueAssets']);

        $controller->render();
    }

    /**
     * Process scripts to be enqueued.
     */
    public function processScripts(): void
    {
        $processedFooterScripts = [];
        foreach ($this->controllerFooterScripts as $handle => $script) {
            $script['handle']         = $handle;
            $script['in_footer']      = true;
            $processedFooterScripts[] = $script;
        }
        $this->controllerFooterScripts = $processedFooterScripts;

        if (!empty($processedFooterScripts)) {
            $this->scripts = $processedFooterScripts;
        }

        $processedHeaderScripts = [];
        foreach ($this->controllerHeaderScripts as $handle => $script) {
            $script['handle']         = $script;
            $script['in_footer']      = false;
            $processedHeaderScripts[] = $script;
        }
        $this->controllerHeaderScripts = $processedHeaderScripts;

        if (empty($this->scripts)) {
            $this->scripts = $processedHeaderScripts;
        } else {
            $this->scripts = array_merge($this->scripts, $processedHeaderScripts);
        }
    }

    /**
     * Process styles to be enqueued.
     */
    public function processStyles(): void
    {
        $registeredStyles = [];
        foreach ($this->controllerStyles as $handle => $style) {
            $style['handle']    = $handle;
            $registeredStyles[] = $style;
        }

        if (empty($this->styles)) {
            $this->styles = $registeredStyles;
        } else {
            $this->styles = array_merge($this->styles, $registeredStyles);
        }
    }

    /**
     * Hooked from wp_enqueue_assets.
     */
    public function enqueueAssets(): void
    {
        $this->enqueueScripts();
        $this->enqueueStyles();
    }

    /**
     * Enqueue scripts.
     */
    protected function enqueueScripts(): void
    {
        foreach ($this->scripts as $script) {
            wp_enqueue_script(
                $script['handle'],
                $script['path'],
                $script['dependencies'],
                $script['version'],
                $script['in_footer']
            );
        }
    }

    /**
     * Enqueue styles.
     */
    protected function enqueueStyles(): void
    {
        foreach ($this->styles as $style) {
            wp_enqueue_style(
                $style['handle'],
                $style['path'],
                $style['dependencies'],
                $style['version']
            );
        }
    }
}
