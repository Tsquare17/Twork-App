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
     * @var array
     */
    public $posts = [];

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
     * Query posts and set fields.
     */
    public function queryPosts()
    {
        if ($this->query->have_posts()) {
            while ($this->query->have_posts()) {
                $this->query->the_post();
                $this->set();
            }
        }
        wp_reset_query();
    }

    /**
     * Specify post property values.
     */
    abstract public function set();

    /**
     * Get posts.
     *
     * @return array
     */
    public function get()
    {
        $this->queryPosts();

        return $this->posts;
    }
}
