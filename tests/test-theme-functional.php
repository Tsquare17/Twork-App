<?php

namespace Twork\Tests;

use WP_UnitTestCase;

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
    public function scripts_load(): void
    {
        $this->assertTrue(wp_script_is('twork-js'));
    }

    /** @test */
    public function styles_load(): void
    {
        $this->assertTrue(wp_style_is('twork-css'));
    }
}
