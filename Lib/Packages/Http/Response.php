<?php

namespace Twork\Packages\Http;

/**
 * Class Response
 * @package Twork\Packages\Http
 */
class Response
{
    /**
     * @var mixed The response body.
     */
    public $body;

    /**
     * @var array The response headers.
     */
    public $headers;

    /**
     * @var int The response status code.
     */
    public $status;

    /**
     * @var string|null The response error.
     */
    public $error;
}
