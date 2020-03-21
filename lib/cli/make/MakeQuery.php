<?php

namespace Twork\Cli\Make;

use Twork\Cli\TworkCli;
use Twork\Utils\Strings;
use WP_CLI;

/**
 * Class MakeController
 * @package Twork\Cli\Make
 */
class MakeQuery extends TworkCli
{
    /**
     * MakeQuery constructor.
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

        $newFile = TWORK_PATH . '/app/queries/' . $name . '.php';

        $this->verifyFileNotExisting($newFile);

        $stub = $this->getStub('Query');

        $templateName = Strings::pascalToKebab($name);
        $step1 = $this->replaceStubPascalCase($stub, $name);

        $replacedStub = $this->replaceStubDashed($step1, $templateName);

        $write = file_put_contents($newFile, $replacedStub);

        if (!$write) {
            WP_CLI::line('Failed to write file ' . $newFile);
            return 0;
        }

        WP_CLI::line('Created query ' . $newFile);
        return 0;
    }
}
