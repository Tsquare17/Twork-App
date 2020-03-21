<?php

namespace Twork\Cli;

use Twork\Cli\Make\MakeController;
use Twork\Cli\Make\MakeCustomPost;
use Twork\Cli\Make\MakeQuery;

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
     *     wp twork make post Example
     *
     * @param array $args
     * @param array $assoc_args
     *
     * @return void
     */
    public function make($args = [], $assoc_args = [])
    {
        $commandExecuted = false;

        if ($this->isCommandWithArgs('controller', 1, $args)) {
            new MakeController($args[1]);
            $commandExecuted = true;
        }

        if ($this->isCommandWithArgs('post', 1, $args)) {
            new MakeCustomPost($args[1]);
            $commandExecuted = true;
        }

        if ($this->isCommandWithArgs('query', 1, $args)) {
            new MakeQuery($args[1]);
            $commandExecuted = true;
        }

        if (!$commandExecuted) {
            WP_CLI::line('command not found...');
        }
    }

    protected function isCommandWithArgs($command, $numberOfArgs, $args)
    {
        if ($args[0] !== $command) {
            return false;
        }

        if (!isset($args[$numberOfArgs])) {
            return false;
        }

        return true;
    }

    protected function verifyFileNotExisting($file)
    {
        if (file_exists($file)) {
            WP_CLI::line($file . ' already exists.');
            return 0;
        }
    }

    public function getStub($name)
    {
        return file_get_contents(TWORK_CLI_PATH . "/make/stubs/{$name}.stub");
    }

    protected function replaceStubPascalCase(string $stub, string $new)
    {
        return preg_replace('/\$\:\$/', $new, $stub);
    }

    protected function replaceStubDashed(string $stub, string $new)
    {
        return preg_replace('/\$\:-\:\$/', $new, $stub);
    }

    protected function replaceStubSpaced(string $stub, string $new)
    {
        return preg_replace('/\$\: \:\$/', $new, $stub);
    }
}
