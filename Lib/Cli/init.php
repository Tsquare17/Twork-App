<?php

/**
 * Extend WP-CLI with TworkCli.
 */

use Twork\Cli\TworkCli;

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::add_command('twork', TworkCli::class);
}
