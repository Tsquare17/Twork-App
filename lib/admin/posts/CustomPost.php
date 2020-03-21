<?php

namespace Twork\Admin\Posts;

use Twork\Utils\Strings;

/**
 * Class CustomPost
 * @package Twork
 */
abstract class CustomPost
{
    /**
     * @var string The post type key.
     */
    protected $postType;

    /**
     * @var bool Whether the post type is visible.
     */
    protected $public = true;

    /**
     * @var string Name for the post type.
     */
    protected $name;

    /**
     * @var string Singular form of the post type name.
     */
    protected $singularName;

    /**
     * @var string A short summary of what the post type is.
     */
    protected $description = '';

    /**
     * @var null The post type's position in the menu.
     */
    protected $menuPosition = null;

    /**
     * @var null The URL to the icon to be used in the menu.
     */
    protected $menuIcon = null;

    /**
     * @var null A callback function for setting up meta boxes.
     */
    protected $registerMetaBoxCallback = null;

    /**
     * CustomPost constructor.
     */
    public function __construct()
    {
        $this->define();
        $this->registerCustomPost();
    }

    /**
     * Register the custom post type.
     */
    protected function registerCustomPost(): void
    {
        $postType = $this->postType;
        $postArgs = [
            'labels' => [
                'name' => __($this->name, 'twork'),
                'singular_name' => __($this->getSingularName(), 'twork')
            ],
            'public' => $this->public,
            'description' => $this->description,
            'menu_position' => $this->menuPosition,
            'menu_icon' => $this->menuIcon,
            'taxonomies' => ['category'],
            'register_meta_box_cb' => $this->registerMetaBoxCallback,
        ];

        add_action('init', static function() use ($postType, $postArgs) {
            register_post_type($postType, $postArgs);
        });
    }

    /**
     * Get the singular name of the post type.
     *
     * @return string
     */
    protected function getSingularName(): string
    {
        return $this->singularName ?? Strings::singular($this->name);
    }

    /**
     * Define the post type properties.
     *
     * @return mixed
     */
    abstract public function define();
}