<?php

namespace Twork\Query;

use WP_Query;

/**
 * Class Query
 * @package Twork\Query
 */
abstract class Query
{
    /**
     * @var WP_Query
     */
    protected $query;

    /**
     * Query constructor.
     *
     * @param string     $type
     * @param array|null $args
     */
    public function __construct($type = 'post', array $args = null)
    {
        $args = $args ?? [
                'posts_per_page' => '10',
                'post_type'      => $type,
            ];

        $this->query = new WP_Query($args);
    }

    /**
     * Get posts.
     */
    public function get(): ?\Generator
    {
        if ($this->query->have_posts()) {
            while ($this->query->have_posts()) {
                $this->query->the_post();
                yield;
            }
        }
        wp_reset_postdata();
    }
}
