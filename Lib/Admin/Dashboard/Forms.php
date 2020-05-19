<?php

namespace Twork\Admin\Dashboard;

use Twork\Utils\StringManipulator;

/**
 * Class Forms
 * @package Twork\Admin\Dashboard
 */
class Forms
{
    /**
     * @var array Registered forms.
     */
    protected $forms = [];

    /**
     * Forms constructor.
     */
    public function __construct()
    {
        $config = require TWORK_PATH . '/Config/config.php';

        foreach ($config['forms'] as $form) {
            $name = substr(strrchr($form, "\\"), 1);
            $name = (new StringManipulator($name))
                ->splitPascal('_')
                ->toLower()
                ->getString();

            $this->forms[] = 'twork_' . $name;
        }
    }
}
