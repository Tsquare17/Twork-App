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

        $newFile = TWORK_PATH . '/App/Controllers/' . $name . 'Controller.php';

        if (file_exists($newFile)) {
            WP_CLI::line($newFile . ' already exists.');
            return 0;
        }

        $stub = $this->getStub('Controller');
        $templateName = Strings::pascalToKebab($name);
        $step1 = $this->replaceStubPascalCase($stub, $name . 'Controller');
        $replacedStub = $this->replaceStubDashed($step1, $templateName);

        $write = file_put_contents($newFile, $replacedStub);

        if (!$write) {
            WP_CLI::line('Failed to write file ' . $newFile);
            return 0;
        }

        WP_CLI::line('Created controller ' . $newFile);

        $configFile = TWORK_PATH . '/Config/config.php';

        $config = fopen($configFile, 'rwb');
        $newConfig = '';
        $afterTemplateLine = false;
        $afterOpen = 0;
        while (!feof($config)) {
            $line = fgets($config);

            if ($afterOpen === 2) {
                $newConfig .= "use Twork\App\Controllers\\{$name}Controller;" . PHP_EOL;
                $afterOpen = false;
            }

            if ($afterTemplateLine) {
                $newConfig .= $line . "        '{$templateName}' => {$name}Controller::class," . PHP_EOL;
                $afterTemplateLine = false;
            } else {
                $newConfig .= $line;
            }

            if (strpos($line, '<?php') !== false) {
                $afterOpen = 1;
            } else {
                $afterOpen++;
            }
            if (strpos($line, "'templates'") !== false) {
                $afterTemplateLine = true;
            }
        }
        fclose($config);

        $writeConfig = file_put_contents(TWORK_PATH . '/Config/config.php', $newConfig);

        if (!$writeConfig) {
            WP_CLI::line("Failed to add '{$templateName}' => {$name}Controller::class");
            return 0;
        }

        WP_CLI::line("Registered '{$templateName}' => {$name}Controller::class");
        return 0;
    }
}
