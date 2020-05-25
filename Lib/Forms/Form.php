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
     * @var int The current fields array index.
     */
    protected $fieldIndex = 0;

    /**
     * @var string The form submit button.
     */
    protected $submitButton = '<button type="submit">Submit</button>';

    /**
     * @var string The form nonce.
     */
    protected $nonce;

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

        $this->nonce = wp_create_nonce($this->formName);
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
     * @param bool $arg
     *
     * @return $this
     */
    public function storeSubmissions(bool $arg = true): Form
    {
        $this->storeSubmissions = $arg;

        return $this;
    }

    /**
     * Return true if the form was submitted.
     *
     * @return bool
     */
    public function isSubmitted(): bool
    {
        return $this->method === 'POST' ? isset($_POST[$this->formName]) : isset($_GET[$this->formName]);
    }

    /**
     * Return true if all fields are valid.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        $vars = $this->method === 'POST' ? $_POST : $_GET;

        if (!wp_verify_nonce($vars[$this->formName], $this->formName)) {
            return false;
        }

        foreach ($this->fields as $field) {
            if ($field['required'] && !isset($vars[$field['name']])) {
                return false;
            }

            $valid = $this->isValidInputType($field['rule'], $vars[$field['name']]);

            if (!$valid) {
                return false;
            }
        }

        return true;
    }

    /**
     * Set an input using a template.
     *
     * @param string $template
     * @param array  $args
     * @param string $inputName
     * @param bool   $required
     * @param int    $validationRule
     *
     * @return $this
     */
    public function inputTemplate(
        string $template,
        array $args,
        string $inputName,
        bool $required = false,
        int $validationRule = null
    ): Form {
        $this->fields[$this->fieldIndex]['template'] = Theme::getBlade()->render($template, $args);
        $this->fields[$this->fieldIndex]['name']     = $inputName;
        $this->fields[$this->fieldIndex]['required'] = $required;
        $this->fields[$this->fieldIndex]['rule']     = $validationRule ?? ValidationRule::TEXT;

        $this->fieldIndex++;

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

        $this->formHtml .= '<input type="hidden" name="' . $this->formName . '" value="' . $this->nonce . '"/>';

        foreach ($this->fields as $field) {
            $this->formHtml .= $field['template'];
        }

        $this->formHtml .= $this->submitButton;

        $this->formHtml .= '</form>';
    }

    /**
     * Return true if value passes validation.
     *
     * @param int $rule
     * @param string $value
     *
     * @return bool
     */
    public function isValidInputType(int $rule, string $value): bool
    {
        switch ($rule) {
            case 0:
                $valid = Validate::text($value);
                break;
            case 1:
                $valid = Validate::email($value);
                break;
            case 2:
                $valid = Validate::phone($value);
                break;
            default:
                $valid = Validate::text($value);
        }

        return $valid;
    }
}
