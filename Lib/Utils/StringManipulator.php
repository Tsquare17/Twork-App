<?php

namespace Twork\Utils;

/**
 * Class StringManipulator
 * @package Twork\Utils
 */
class StringManipulator
{
    /**
     * @var string The string to manipulate.
     */
    protected $string;

    /**
     * StringManipulator constructor.
     *
     * @param string $string
     */
    public function __construct(string $string)
    {
        $this->string = $string;
    }

    /**
     * Transform string to lower case.
     *
     * @return StringManipulator
     */
    public function toLower(): StringManipulator
    {
        $this->string = strtolower($this->string);

        return $this;
    }

    /**
     * Transform string to upper case.
     *
     * @return StringManipulator
     */
    public function toUpper(): StringManipulator
    {
        $this->string = strtoupper($this->string);

        return $this;
    }

    /**
     * Transform a pascal case string to be separated by the specified glue.
     *
     * @param string $glue
     *
     * @return StringManipulator
     */
    public function splitPascal(string $glue): StringManipulator
    {
        $this->string = Strings::splitPascal($this->string, $glue);

        return $this;
    }

    /**
     * Get the string.
     *
     * @return string
     */
    public function getString(): string
    {
        return $this->string;
    }
}
