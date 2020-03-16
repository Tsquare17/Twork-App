<?php

namespace Twork;

use Jenssegers\Blade\Blade;
use Twork\Template\Interceptor;

/**
 * Class Theme
 * @package Twork
 */
class Theme
{
    /**
     * @var array Scripts to be enqueued for the template, in the footer.
     */
    public $footerScripts = [];

    /**
     * @var array Scripts to be enqueued for the template, in the header.
     */
    public $headerScripts = [];

    /**
     * @var array Styles to be enqueued for the template.
     */
    public $styles = [];

    /**
     * Theme constructor.
     *
     * Collect controller files and instantiate, registering blade templates.
     */
    public function __construct()
    {
        $controllerPath = TWORK_PATH . '/app/controller';

        $files = array_diff(scandir($controllerPath), ['.', '..']);

        foreach ($files as $file) {
            $controller = 'Twork\\App\\Controller\\' . str_replace('.php', '', $file);
            new $controller;
        }
    }

    /**
     * Override WordPress template includes.
     *
     * @param $templateToOverride
     * @param $overrideTemplate
     */
    public function registerTemplate($templateToOverride, $overrideTemplate)
    {
        $blade = self::getBlade();

        $data = $this->data();

        if (strpos(strrev($templateToOverride), 'php.') !== 0) {
            $templateToOverride .= '.php';
        }

        $this->processScripts();

        $interceptor = new Interceptor($templateToOverride, $overrideTemplate, $blade, $data);
        $interceptor->registerScripts($this->footerScripts);
        $interceptor->registerScripts($this->headerScripts);
        $interceptor->registerStyles($this->styles());

        add_filter('template_include', [$interceptor, 'templateInterceptor']);
    }

    /**
     * Return an array of variables to pass to the template.
     *
     * @return array
     */
    public function data()
    {
        return [];
    }

    /**
     * Scripts to be enqueued in the footer.
     *
     * @return array
     */
    public function footerScripts()
    {
        return [];
    }

    /**
     * Scripts to be enqueued in the header.
     *
     * @return array
     */
    public function headerScripts()
    {
        return [];
    }

    /**
     * Styles to be enqueued.
     *
     * @return array
     */
    public function styles()
    {
        return [];
    }

    /**
     * Process the scripts specified for the template.
     */
    protected function processScripts()
    {
        $processedFooterScripts = [];
        foreach ($this->footerScripts() as $handle => $script) {
            $processedFooterScripts[$handle] = $script;
            $processedFooterScripts[$handle]['in_footer'] = true;
        }
        $this->footerScripts = $processedFooterScripts;

        $processedHeaderScripts = [];
        foreach ($this->headerScripts() as $handle => $script) {
            $processedHeaderScripts[$handle] = $script;
            $processedHeaderScripts[$handle]['in_footer'] = false;
        }
        $this->headerScripts = $processedHeaderScripts;
    }

    /**
     * Get Blade with the theme defaults.
     *
     * @return Blade
     */
    public static function getBlade()
    {
        $blade = new Blade(TWORK_PATH . '/resources/views', TWORK_PATH . '/cache');

        $path = TWORK_PATH . '/lib/blade/';

        $files = array_diff(scandir($path), ['.', '..']);

        foreach ($files as $file) {
            include $path . $file;
        }

        return $blade;
    }

    /**
     * Shorthand script specification.
     *
     * @param       $path
     * @param array $dependencies
     *
     * @return array
     */
    protected function script($path, array $dependencies = null) {
        return ['path' => TWORK_JS_URL . $path, 'dependencies' => $dependencies, 'version' => TWORK_VERSION];
    }

    /**
     * Shorthand style specification.
     *
     * @param            $path
     * @param array|null $dependencies
     *
     * @return array
     */
    protected function style($path, array $dependencies = null)
    {
        return ['path' => TWORK_CSS_URL . $path, 'dependencies' => $dependencies, 'version' => TWORK_VERSION];
    }
}
