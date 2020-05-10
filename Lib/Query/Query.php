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
     * @var array The original WP_Query arguments.
     */
    protected $originalArgs;

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

        $this->originalArgs = $this->args;
    }

    /**
     * Execute the query.
     *
     * @return Query
     */
    public function execute(): Query
    {
        $this->query = $this->query ?: new WP_Query($this->args);

        return $this;
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
     * Add to the query args.
     *
     * @param string       $key
     * @param string|array $value
     * @param null|string  $parent
     *
     * @return Query
     */
    public function addArg($key, $value, $parent = null): Query
    {
        if ($value && !$parent) {
            $this->args[$key] = $value;
        } elseif ($value && $parent) {
            $this->args[$parent][$key] = $value;
        }

        return $this;
    }

    /**
     * Add raw query args.
     *
     * @param $args
     *
     * @return Query
     */
    public function addRawArgs($args): Query
    {
        $this->args[] = $args;

        return $this;
    }

    /**
     * Get posts.
     *
     * @return Generator|null
     */
    public function get(): ?Generator
    {
        $this->query = $this->query ?: new WP_Query($this->args);

        if ($this->query->have_posts()) {
            while ($this->query->have_posts()) {
                $this->query->the_post();
                yield;
            }

            wp_reset_postdata();
        }
    }

    /**
     * Reset the query.
     *
     * @return Query
     */
    public function reset(): Query
    {
        $this->args = $this->originalArgs;

        $this->query = null;

        return $this;
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
     *
     * @return Query
     */
    public function category($category): Query
    {
        if (is_numeric($category)) {
            $this->addArg('cat', $category);
        } else {
            $this->addArg('category_name', $category);
        }

        return $this;
    }

    /**
     * Set a search term.
     *
     * @param $search
     *
     * @return Query
     */
    public function search($search): Query
    {
        $this->addArg('s', $search);

        return $this;
    }

    /**
     * Add a meta query.
     *
     * @param string|array $args
     *
     * @return Query
     */
    public function metaQuery($args): Query
    {
        if (is_array($args)) {
            $this->args['meta_query'][] = $args;
        } else {
            $this->args['meta_query'] = $args;
        }

        return $this;
    }

    /**
     * Query for author(s).
     *
     * @param $args
     *
     * @return Query
     */
    public function author($args): Query
    {
        if (is_string($args)) {
            $this->addArg('author_name', $args);
        } elseif (is_int($args)) {
            $this->addArg('author', $args);
        } else {
            $this->addArg('author', implode(',', $args));
        }

        return $this;
    }

    /**
     * Get the number of posts.
     *
     * @return int
     */
    public function count(): int
    {
        $this->query = $this->query ?: new WP_Query($this->args);

        return $this->query->found_posts;
    }

    /**
     * Get the number of pages of posts.
     *
     * @return int
     */
    public function pages(): int
    {
        $this->query = $this->query ?: new WP_Query($this->args);

        return $this->query->max_num_pages;
    }
}
