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
     * Initialize Theme.
     */
    public function __construct()
    {
        $config = require TWORK_PATH . '/Config/config.php';

        foreach ($config['custom_posts'] as $customPost) {
            new $customPost();
        }

        foreach ($config['dashboard_menus'] as $dashboardMenu) {
            new $dashboardMenu();
        }

        new Interceptor($config['templates']);
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
