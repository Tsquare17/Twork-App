<?php

namespace Twork\Exceptions;

use Exception;

class FileNotFoundException extends Exception
{
    public function __construct(string $path, int $code = 0, Exception $previous = null)
    {
        $message = sprintf('File %s not found.', $path);

        parent::__construct($message, $code, $previous);
    }
}
