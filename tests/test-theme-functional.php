<?php

namespace Twork\Tests;

use WP_UnitTestCase;
use Twork\App\Queries\CustomPost;

/**
 * Class ThemeFunctionalTest
 *
 * Basic theme functionality test case.
 *
 * @package Twork
 */
class ThemeFunctionalTest extends WP_UnitTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        switch_theme('twork');

        do_action('wp_enqueue_scripts');
    }

    /** @test */
    public function theme_loaded(): void
    {
        $this->assertSame('twork', wp_get_theme()->get('Name'));
    }

    /** @test */
    public function homepage_loads(): void
    {
        $this->go_to('/');

        $this->assertQueryTrue('is_home', 'is_front_page');
    }

    /** @test */
    public function scripts_load(): void
    {
        $this->assertTrue(wp_script_is('twork-js'));
    }

    /** @test */
    public function styles_load(): void
    {
        $this->assertTrue(wp_style_is('twork-css'));
    }

    /** @test */
    public function custom_post_is_registered(): void
    {
        $this->assertTrue(post_type_exists('custom-post'));
    }
}
