<?php

use Twork\Debug\DebugMail;
use Twork\Setup;

include 'vendor/autoload.php';
include 'Lib/defines.php';
include 'Lib/Cli/init.php';
include 'Lib/Functions/init.php';

new Setup();

if (defined('WP_DEBUG') && true === WP_DEBUG) {
    new DebugMail();
}
