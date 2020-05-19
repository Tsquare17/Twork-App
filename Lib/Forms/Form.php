<?php

namespace Twork\Forms;

use ReflectionClass;
use ReflectionException;
use Twork\Theme;
use Twork\Utils\StringManipulator;

/**
 * Class Forms
 * @package Twork
 */
class Form
{
    /**
     * @var string An identifier for the form.
     */
    protected $formName;

    /**
     * @var bool If true, store submissions in the database.
     */
    protected $storeSubmissions = false;

    /**
     * @var string The form HTML.
     */
    protected $formHtml;

    /**
     * @var string The form method.
     */
    protected $method = 'POST';

    /**
     * @var string The form action.
     */
    protected $action = '';

    /**
     * @var array The form fields.
     */
    protected $fields = [];

    /**
     * @var string The form submit button.
     */
    protected $submitButton = '<button type="submit">Submit</button>';

    /**
     * Form constructor.
     *
     * @param null $formName
     *
     * @throws ReflectionException
     */
    public function __construct($formName = null)
    {
        if (!$formName) {
            $name = (new ReflectionClass($this))->getShortName();
            $name = (new StringManipulator($name))
                ->splitPascal('_')
                ->toLower()
                ->getString();

            $this->formName = $name;
        } else {
            $this->formName = 'twork_' . $formName;
        }
    }

    /**
     * Set the form method.
     *
     * @param $method
     *
     * @return $this
     */
    public function method($method): Form
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Set the form action.
     *
     * @param string $action
     *
     * @return $this
     */
    public function action(string $action): Form
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Store submissions in the database.
     *
     * @param bool $args
     *
     * @return $this
     */
    public function storeSubmissions(bool $args = true): Form
    {
        $this->storeSubmissions = $args;

        return $this;
    }

    /**
     * Return true if the form was submitted.
     *
     * @return bool
     */
    public function isSubmitted(): bool
    {
        return isset($_POST[$this->formName]);
    }

    /**
     * Set an input using a template.
     *
     * @param $template
     * @param mixed ...$args
     *
     * @return $this
     */
    public function inputTemplate($template, ...$args): Form
    {
        $this->fields[] = Theme::getBlade()->render($template, ...$args);

        return $this;
    }

    /**
     * Output the form.
     */
    public function render(): void
    {
        $this->buildForm();

        echo $this->formHtml;
    }

    /**
     * Build the form HTML.
     *
     * @param string $classes
     */
    public function buildForm(string $classes = ''): void
    {
        $this->formHtml = '<form action="' . $this->action . '" method="' . $this->method . '" class="' . $classes
                          . '">';

        $this->formHtml .= '<input type="hidden" name="' . $this->formName . '" value="1"/>';

        foreach ($this->fields as $field) {
            $this->formHtml .= $field;
        }

        $this->formHtml .= $this->submitButton;

        $this->formHtml .= '</form>';
    }
}
