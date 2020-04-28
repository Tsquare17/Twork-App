<?php

namespace Twork\Query;

use Generator;
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
     * @var array WP_Query arguments.
     */
    protected $args;

    /**
     * @var string Post type.
     */
    protected $postType;

    /**
     * @var int Number of posts per page.
     */
    protected $postsPerPage;

    /**
     * Query constructor.
     *
     * @param string     $type
     * @param array|null $args
     */
    public function __construct($type = 'post', array $args = null)
    {
        $this->postType = $type;

        if (!$args) {
            $this->collectQueryArgs();
        } else {
            $this->args = $args;
        }
    }

    /**
     * Build an array of query args.
     */
    public function collectQueryArgs(): void
    {
        $this->args = [
            'post_type' => $this->postType,
            'paged'     => get_query_var('paged') ? absint(get_query_var('paged')) : 1,
        ];

        $this->addArg('posts_per_page', $this->postsPerPage);
    }

    /**
     * Add an argument to the query args, if a value exists for it.
     *
     * @param string $key
     * @param string|array $value
     * @param null|string $parent
     */
    public function addArg($key, $value, $parent = null): void
    {
        if ($value && !$parent) {
            $this->args[$key] = $value;
        } elseif ($value && $parent) {
            $this->args[$parent][$key] = $value;
        }
    }

    /**
     * Get posts.
     *
     * @return Generator|null
     */
    public function get(): ?Generator
    {
        $this->query = new WP_Query($this->args);

        if ($this->query->have_posts()) {
            while ($this->query->have_posts()) {
                $this->query->the_post();
                yield;
            }

            wp_reset_postdata();
        }
    }

    /**
     * Get pagination links.
     *
     * @param int|null $total
     * @param string|null $previousText
     * @param string|null $nextText
     *
     * @return array|string|void
     */
    public function pagination(int $total = null, string $previousText = null, string $nextText = null)
    {
        $args = [
            'total' => $total ?? $this->query->max_num_pages,
        ];

        if ($previousText) {
            $args['prev_text'] = __($previousText, 'twork');
        }

        if ($nextText) {
            $args['next_text'] = __($nextText, 'twork');
        }

        return paginate_links($args);
    }

    /**
     * Set the category of posts to query.
     *
     * @param $category
     */
    public function category($category): void
    {
        if (is_numeric($category)) {
            $this->args['cat'] = $category;
        } else {
            $this->args['category_name'] = $category;
        }
    }
}
