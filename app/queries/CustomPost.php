<?php

namespace Twork\Queries;

use Twork\Query\Query;

/**
 * Class CustomPost
 * @package Twork\Query
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

    /**
     * Set fields and values.
     *
     * @param $i
     */
    public function set($i) {
        $this->posts[$i]['id'] = get_the_ID();
        $this->posts[$i]['title'] = get_the_title();
    }
}
