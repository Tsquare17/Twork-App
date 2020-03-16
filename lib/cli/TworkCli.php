<?php

namespace Twork\Cli;

use Twork\Cli\Make\MakeController;

/**
 * Class TworkCli
 * @package Twork\Cli
 */
class TworkCli
{
    /**
     * Makes things.
     *
     * ## OPTIONS
     *
     * <params>...
     * : The thing to be made, followed by the name.
     *
     * ## EXAMPLES
     *
     *     wp twork make controller Example
     *
     * @param array $args
     * @param array $assoc_args
     *
     * @return void
     */
    public function make($args = [], $assoc_args = [])
    {
        if (($args[0] === 'controller') && isset($args[1])) {
            new MakeController($args[1]);
        }
    }

    protected function getControllerStub()
    {
        return file_get_contents(TWORK_CLI_PATH . '/make/stubs/Controller.stub');
    }

    protected function replaceStubPascalCase(string $stub, string $new)
    {
        return preg_replace('/\$\:\$/', $new, $stub);
    }

    protected function replaceStubDashed(string $stub, string $new)
    {
        return preg_replace('/\$\:-\:\$/', $new, $stub);
    }
}
