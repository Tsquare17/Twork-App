<?php

namespace Twork\App\Queries;

use Twork\Query\Post;
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

    /**
     * Set post property values.
     */
    public function set()
    {
        $post = new Post();

        $post->id = get_the_ID();
        $post->title = get_the_title();

        $this->posts[] = $post;
    }
}
