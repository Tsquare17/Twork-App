<?php

namespace Twork\App\Queries;

use Twork\Query\Query;

/**
 * Class CustomPost
 * @package Twork\App\Queries
 */
class CustomPost extends Query
{
    /**
     * CustomPost constructor.
     */
    public function __construct()
    {
        parent::__construct('custom-post');
    }
}
