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
     * Theme constructor.
     *
     * Initialize Theme's Custom Posts and Templates.
     */
    public function __construct()
    {
        $config = require TWORK_PATH . '/Config/config.php';

        $this->registerCustomPosts();

        foreach ($config['templates'] as $template => $controller) {
            $this->overrideTemplate($template, $config['templates'], $controller);
        }
    }

    /**
     * Register Custom Post Types.
     */
    public function registerCustomPosts(): void
    {
        $files = array_diff(scandir(TWORK_PATH . '/App/Posts'), ['.', '..']);
        foreach ($files as $file) {
            $class = 'Twork\\App\\Posts\\' . str_replace('.php', '', $file);
            new $class();
        }
    }

    /**
     * Override WordPress template includes.
     *
     * @param $template
     * @param $pageTemplates
     * @param $controller
     */
    public function overrideTemplate($template, $pageTemplates, $controller): void
    {
        if (strpos(strrev($template), 'php.') !== 0) {
            $template .= '.php';
        }

        new Interceptor($template, $pageTemplates, $controller);
    }

    /**
     * Get Blade with the theme defaults.
     *
     * @return Blade
     */
    public static function getBlade(): Blade
    {
        $blade = new Blade(TWORK_PATH . '/resources/views', TWORK_PATH . '/cache');

        $path = TWORK_PATH . '/Lib/Blade/';

        $files = array_diff(scandir($path), ['.', '..']);

        foreach ($files as $file) {
            include $path . $file;
        }

        return $blade;
    }
}
