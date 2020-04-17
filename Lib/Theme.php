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

        foreach ($config['custom_posts'] as $customPost) {
            new $customPost;
        }

        foreach ($config['dashboard_menus'] as $dashboardMenu) {
            new $dashboardMenu;
        }

        foreach ($config['templates'] as $template => $controller) {
            $this->overrideTemplate($template, $controller);
        }
    }

    /**
     * Override WordPress template includes.
     *
     * @param $template
     * @param $controller
     */
    public function overrideTemplate($template, $controller): void
    {
        if (strpos(strrev($template), 'php.') !== 0) {
            $template .= '.php';
        }

        new Interceptor($template, $controller);
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
