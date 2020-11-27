<?php

use Twork\App\Admin\Menus\ExampleMenu;
use Twork\Theme\Setup\Setup;

include 'vendor/autoload.php';
include 'Lib/defines.php';
include 'Lib/custom-posts.php';

new Setup();

new ExampleMenu();
