<?php

namespace Twork\Template;

use Twork\Theme;

class Interceptor
{
    protected $template;
    protected $pageTemplates;

	/**
	 * @var Theme $controller
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
        $this->template = $template;
        $this->pageTemplates = $pageTemplates;
        $this->controller = new $controller();

        add_filter('template_include', [$this, 'templateInterceptor']);
    }

    public function templateInterceptor($template)
    {
        if ($template === TWORK_PATH . '/' . $this->template) {
            $this->runController();
            return;
        }

        return $template;
    }

    public function runController()
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
	public function processScripts()
	{
		$processedFooterScripts = [];
		foreach ($this->controllerFooterScripts as $handle => $script) {
			$processedFooterScripts[$handle] = $script;
			$processedFooterScripts[$handle]['in_footer'] = true;
		}
		$this->controllerFooterScripts = $processedFooterScripts;

		$processedHeaderScripts = [];
		foreach ($this->controllerHeaderScripts as $handle => $script) {
			$processedHeaderScripts[$handle] = $script;
			$processedHeaderScripts[$handle]['in_footer'] = false;
		}
		$this->controllerHeaderScripts = $processedHeaderScripts;
	}


	public function registerScripts(array $scripts)
    {
        if (empty($scripts)) {
            return;
        }

        $registeredScripts = [];
        foreach ($scripts as $handle => $script) {
            $script['handle'] = $handle;
            $registeredScripts[] = $script;
        }

        if (empty($this->scripts)) {
            $this->scripts = $registeredScripts;
        } else {
            $this->scripts = array_merge($this->scripts, $registeredScripts);
        }
    }

    public function registerStyles(array $styles)
    {
        $registeredStyles = [];
        foreach ($styles as $handle => $style) {
            $style['handle'] = $handle;
            $registeredStyles[] = $style;
        }

        if (empty($this->styles)) {
            $this->styles = $registeredStyles;
        } else {
            $this->styles = array_merge($this->styles, $registeredStyles);
        }
    }

    public function enqueueAssets()
    {
        $this->enqueueScripts();
        $this->enqueueStyles();
    }

    protected function enqueueScripts()
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

    protected function enqueueStyles()
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
