<?php

namespace Twork\Template;

use Twork\Controller;

class Interceptor
{
    protected $template;
    protected $pageTemplates;

    /**
     * @var Controller $controller
     */
    protected $controller;
    protected $blade;
    protected $data;
    protected $scripts;
    protected $styles;
    protected $controllerFooterScripts;
    protected $controllerHeaderScripts;
    protected $controllerStyles;

    public function __construct($template, $pageTemplates, $controller)
    {
        $this->template      = $template;
        $this->pageTemplates = $pageTemplates;
        $this->controller    = new $controller();

        add_filter('template_include', [$this, 'templateInterceptor']);
    }

    /**
     * template_include filter.
     *
     * @param $template
     */
    public function templateInterceptor($template)
    {
        if ($template === TWORK_PATH . '/' . $this->template) {
            $this->runController();

            return;
        }

        return $template;
    }

    /**
     * Collect and enqueue assets, and render the template.
     */
    public function runController(): void
    {
        $controller = $this->controller;

        $this->controllerFooterScripts = $controller->footerScripts();
        $this->controllerHeaderScripts = $controller->headerScripts();
        $this->controllerStyles        = $controller->styles();
        $this->processScripts();

        $this->registerScripts($this->controllerFooterScripts);
        $this->registerScripts($this->controllerHeaderScripts);
        $this->registerStyles($this->controllerStyles);

        add_action('wp_enqueue_scripts', [$this, 'enqueueAssets']);

        $controller->render();
    }

    /**
     * Process the scripts specified for the template.
     */
    public function processScripts(): void
    {
        $processedFooterScripts = [];
        foreach ($this->controllerFooterScripts as $handle => $script) {
            $processedFooterScripts[$handle]              = $script;
            $processedFooterScripts[$handle]['in_footer'] = true;
        }
        $this->controllerFooterScripts = $processedFooterScripts;

        $processedHeaderScripts = [];
        foreach ($this->controllerHeaderScripts as $handle => $script) {
            $processedHeaderScripts[$handle]              = $script;
            $processedHeaderScripts[$handle]['in_footer'] = false;
        }
        $this->controllerHeaderScripts = $processedHeaderScripts;
    }

    /**
     * @param array $scripts
     */
    public function registerScripts(array $scripts): void
    {
        if (empty($scripts)) {
            return;
        }

        $registeredScripts = [];
        foreach ($scripts as $handle => $script) {
            $script['handle']    = $handle;
            $registeredScripts[] = $script;
        }

        if (empty($this->scripts)) {
            $this->scripts = $registeredScripts;
        } else {
            $this->scripts = array_merge($this->scripts, $registeredScripts);
        }
    }

    /**
     * @param array $styles
     */
    public function registerStyles(array $styles): void
    {
        $registeredStyles = [];
        foreach ($styles as $handle => $style) {
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
        if (empty($this->scripts)) {
            return;
        }

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
