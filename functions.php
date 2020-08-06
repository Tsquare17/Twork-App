<?php

use Twork\Debug\DebugMail;
use Twork\Setup;

include 'vendor/autoload.php';
include 'Lib/defines.php';
include 'Lib/Cli/init.php';
include 'Lib/Functions/init.php';

new Setup();

if (defined('TWORK_DEBUG_MAIL') && true === TWORK_DEBUG_MAIL) {
    new DebugMail();
}
