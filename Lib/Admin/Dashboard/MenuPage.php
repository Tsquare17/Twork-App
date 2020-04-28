<?php

namespace Twork\Admin\Dashboard;

/**
 * Class MenuPage
 * @package Twork\Admin\Dashboard
 */
abstract class MenuPage
{
    /**
     * @var string The title of the page.
     */
    protected $pageTitle;

    /**
     * @var string The title of the menu in the dashboard sidebar.
     */
    protected $menuTitle;

    /**
     * @var string The permission level required to view the page.
     */
    protected $capability = 'manage_options';

    /**
     * @var string The slug of the page.
     */
    protected $menuSlug;

    /**
     * @var string The dashicon string for the icon of the menu item.
     */
    protected $icon = 'dashicons-admin-generic';

    /**
     * @var int The position of the menu item.
     */
    protected $position = 5;

    /**
     * MenuPage constructor.
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register']);
        add_action('admin_enqueue_scripts', [$this, 'scripts']);
        add_action('admin_init', [$this, 'actions']);
    }

    /**
     * Register the menu page.
     */
    public function register(): void
    {
        add_menu_page(
            $this->pageTitle ?: $this->menuTitle,
            $this->menuTitle,
            $this->capability,
            $this->menuSlug ?: str_replace(' ', '-', strtolower($this->menuTitle)),
            [$this, 'view'],
            $this->icon,
            $this->position
        );
    }

    /**
     * Display for the menu item page.
     */
    abstract public function view();

    /**
     * Actions to run on admin_init.
     */
    public function actions()
    {
    }

    /**
     * Scripts to enqueue.
     */
    public function scripts()
    {
    }
}
