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
    public static function pascalToKebab($string)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);

        foreach ($matches[0] as &$match) {
            $match = ($match === strtoupper($match)) ? strtolower($match) : lcfirst($match);
        }

        return implode('-', $matches[0]);
    }
}
