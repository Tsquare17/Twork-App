<?php

namespace Twork\Cli\Make;

use Twork\Cli\TworkCli;
use Twork\Utils\Strings;
use WP_CLI;

/**
 * Class MakeController
 * @package Twork\Cli\Make
 */
class MakeCustomPost extends TworkCli
{
    /**
     * MakeCustomPost constructor.
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

        $newFile = TWORK_PATH . '/app/posts/' . $name . '.php';

        if (file_exists($newFile)) {
            WP_CLI::line($newFile . ' already exists.');
            return 0;
        }

        $stub = $this->getCustomPostStub();

        $templateName = Strings::pascalToKebab($name);
        $step1 = $this->replaceStubPascalCase($stub, $name);

        $label = Strings::splitPascal($name, ' ');
        $step2 = $this->replaceStubSpaced($step1, $label);

        $replacedStub = $this->replaceStubDashed($step2, $templateName);

        $write = file_put_contents($newFile, $replacedStub);

        if (!$write) {
            WP_CLI::line('Failed to write file ' . $newFile);
            return 0;
        }

        WP_CLI::line('Created custom post ' . $newFile);
        return 0;
    }

    protected function getCustomPostStub()
    {
        return file_get_contents(TWORK_CLI_PATH . '/make/stubs/CustomPost.stub');
    }
}
