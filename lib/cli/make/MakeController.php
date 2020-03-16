<?php

namespace Twork\Cli\Make;

use Twork\Cli\TworkCli;
use Twork\Utils\Strings;
use WP_CLI;

/**
 * Class MakeController
 * @package Twork\Cli\Make
 */
class MakeController extends TworkCli
{
    /**
     * MakeController constructor.
     *
     * @param $name
     */
    public function __construct($name)
    {
        $this->execute($name);
    }

    /**
     * @param $name
     *
     * @return int
     */
    public function execute($name)
    {
        if (strpos(strrev($name), strrev('Controller')) === 0) {
            $name = str_replace('Controller', '', $name);
        }

        $newFile = TWORK_PATH . '/app/controller/' . $name . 'Controller.php';

        if (file_exists($newFile)) {
            WP_CLI::line($newFile . ' already exists.');
            return 0;
        }

        $stub = $this->getControllerStub();

        $step1 = $this->replaceStubPascalCase($stub, $name . 'Controller');
        $replacedStub = $this->replaceStubDashed($step1, Strings::pascalToKebab($name));

        $write = file_put_contents($newFile, $replacedStub);

        if (!$write) {
            WP_CLI::line('Failed to write file ' . $newFile);
            return 0;
        }

        WP_CLI::line('Created controller ' . $newFile);
        return 0;
    }
}
