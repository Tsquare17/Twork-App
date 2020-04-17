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

        $newFile = TWORK_PATH . '/App/Posts/' . $name . '.php';

        if (file_exists($newFile)) {
            WP_CLI::line($newFile . ' already exists.');
            return 0;
        }

        $stub = $this->getStub('CustomPost');

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

        $configFile = TWORK_PATH . '/Config/config.php';

        $config = fopen($configFile, 'rwb');
        $newConfig = '';
        $afterMarkLine = false;
        $afterOpen = 0;
        while (!feof($config)) {
            $line = fgets($config);

            if ($afterOpen === 2) {
                $newConfig .= "use Twork\App\Posts\\{$name};" . PHP_EOL;
                $afterOpen = false;
            }

            if ($afterMarkLine) {
                $newConfig .= $line . "        {$name}::class," . PHP_EOL;
                $afterMarkLine = false;
            } else {
                $newConfig .= $line;
            }

            if (strpos($line, '<?php') !== false) {
                $afterOpen = 1;
            } else {
                $afterOpen++;
            }
            if (strpos($line, "'custom_posts'") !== false) {
                $afterMarkLine = true;
            }
        }
        fclose($config);

        $writeConfig = file_put_contents(TWORK_PATH . '/Config/config.php', $newConfig);

        if (!$writeConfig) {
            WP_CLI::line("Failed to write {$name}::class to config");
            return 0;
        }

        return 0;
    }
}
