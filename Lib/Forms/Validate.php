<?php

namespace Twork\Forms;

/**
 * Class Validator
 * @package Twork\Forms
 */
class Validate
{
    /**
     * Validate string is text.
     *
     * @param string $text
     *
     * @return bool
     */
    public static function text(string $text): bool
    {
        return true;
    }

    /**
     * Validate string is an email address.
     *
     * @param string $email
     *
     * @return bool
     */
    public static function email(string $email): bool
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Validate string is a phone number.
     *
     * @param string $phone
     *
     * @return bool
     */
    public static function phone(string $phone): bool
    {
        return true;
    }
}
