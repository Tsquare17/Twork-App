<?php

namespace Twork\Exceptions;

use Exception;

/**
 * Class FileNotFoundException
 * @package Twork\Exceptions
 */
class FileNotFoundException extends Exception
{
    /**
     * FileNotFoundException constructor.
     *
     * @param string         $path
     * @param int            $code
     * @param Exception|null $previous
     */
    public function __construct(string $path, int $code = 0, Exception $previous = null)
    {
        $message = sprintf('File %s not found.', $path);

        parent::__construct($message, $code, $previous);
    }
}
