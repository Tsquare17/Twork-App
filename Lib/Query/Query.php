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

        $args = $args ?? $this->queryArgs();

        $this->query = new WP_Query($args);
    }

    /**
     * Build an array of query args.
     *
     * @return array
     */
    public function queryArgs(): array
    {
        $args = [
            'post_type' => $this->postType,
            'paged'     => get_query_var('paged') ? absint(get_query_var('paged')) : 1,
        ];

        $args = $this->addArg($args, 'posts_per_page', $this->postsPerPage);

        return $args;
    }

    /**
     * Add an argument to the query args, if a value exists for it.
     *
     * @param array $args
     * @param string $key
     * @param string|array $value
     * @param null|string $parent
     *
     * @return array
     */
    public function addArg($args, $key, $value, $parent = null): array
    {
        if ($value && !$parent) {
            $args[$key] = $value;
        } elseif ($value && $parent) {
            $args[$parent][$key] = $value;
        }

        return $args;
    }

    /**
     * Get posts.
     *
     * @return Generator|null
     */
    public function get(): ?Generator
    {
        if ($this->query->have_posts()) {
            while ($this->query->have_posts()) {
                $this->query->the_post();
                yield;
            }

            wp_reset_postdata();
        }
    }

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
}
