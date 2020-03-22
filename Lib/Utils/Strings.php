<?php

namespace Twork\Utils;

/**
 * Class Strings
 * @package Twork\Utils
 */
class Strings
{
    /**
     * Take a string in PascalCase and return it in kebab-case.
     *
     * @param $string
     *
     * @return string
     */
    public static function pascalToKebab($string): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);

        foreach ($matches[0] as &$match) {
            $match = ($match === strtoupper($match)) ? strtolower($match) : lcfirst($match);
        }

        return implode('-', $matches[0]);
    }

    /**
     * Split a PascalCase string with the specified glue.
     *
     * @param $string
     * @param $glue
     *
     * @return string
     */
    public static function splitPascal($string, $glue): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);

        return implode($glue, $matches[0]);
    }

    /**
     * Attempt to return the singular form of a word.
     *
     * @param $string
     *
     * @return string
     */
    public static function singular($string): string
    {
        if (strpos(strrev($string), 'sei') === 0) {
            return substr($string, 0, -3) . 'y';
        }

        if (strpos(strrev($string), 's') === 0) {
            return substr($string, 0, -1);
        }

        return $string;
    }
}
