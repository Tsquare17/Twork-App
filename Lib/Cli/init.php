<?php

use Twork\Cli\TworkCli;

/**
 * Extend WP-CLI with TworkCli.
 */
if (defined( 'WP_CLI' ) && WP_CLI ) {
    WP_CLI::add_command('twork', TworkCli::class);
}
