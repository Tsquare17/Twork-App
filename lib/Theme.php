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
     * @var string Name of the blade template.
     */
    public $template;

    /**
     * Theme constructor.
     *
     * Loop over template-controller map, registering blade templates.
     */
    public function __construct()
    {
        $config = require TWORK_PATH . '/config/config.php';

        foreach ($config['templates'] as $template => $controller) {
            $this->registerTemplate($template, $controller);
        }
    }

    /**
     * Override WordPress template includes.
     *
     * @param $template
     * @param $controller
     */
    public function registerTemplate($template, $controller)
    {
        if (strpos(strrev($template), 'php.') !== 0) {
            $template .= '.php';
        }

        $interceptor = new Interceptor($template, $controller);
        $interceptor->dispatch();
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

    /**
     * Render Blade template.
     */
    public function render()
    {
        echo self::getBlade()->render($this->template, $this->data());
    }
}
