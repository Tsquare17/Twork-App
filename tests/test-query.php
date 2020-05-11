<?php

namespace Twork\Tests;

use WP_UnitTestCase;
use Twork\App\Queries\CustomPost;

/**
 * Class QueryTest
 *
 * Query test case.
 *
 * @package Twork
 */
class QueryTest extends WP_UnitTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        switch_theme('twork');
    }

    /** @test */
    public function query_returns_results(): void
    {
        $this->factory->post->create_many(3, ['post_type' => 'custom-post']);

        $query = new CustomPost();

        $this->assertSame(3, $query->count());
    }

    /** @test */
    public function can_query_authors_posts(): void
    {
        $user = $this->factory->user->create();
        $otherUser = $this->factory->user->create();

        $this->factory->post->create_many(2, [
            'post_author' => $user,
            'post_type'   => 'custom-post',
        ]);

        $this->factory->post->create_many(4, [
            'post_author' => $otherUser,
            'post_type'   => 'custom-post',
        ]);

        $query = new CustomPost();
        $query->author($user);

        foreach ($query->get() as $post) {
            $this->assertSame($user, get_the_author_meta('ID'));
        }
    }

    /** @test */
    public function can_query_by_category(): void
    {
        $cat = $this->factory->category->create();
        $otherCat = $this->factory->category->create();

        $this->factory->post->create_many(2, [
            'post_category' => [$cat],
            'post_type'     => 'custom-post',
        ]);

        $this->factory->post->create_many(4, [
            'post_category' => [$otherCat],
            'post_type'     => 'custom-post',
        ]);

        $query = new CustomPost();
        $query->category($cat);

        foreach ($query->get() as $post) {
            $this->assertSame($cat, get_the_category()[0]->term_taxonomy_id);
        }

        $query->reset();

        $query->category($otherCat);

        foreach ($query->get() as $post) {
            $this->assertSame($otherCat, get_the_category()[0]->term_taxonomy_id);
        }
    }

    /** @test */
    public function can_query_posts_by_search_term(): void
    {
        $searchTerm = '12test20342';
        $otherSearchTerm = '415948082';

        $this->factory->post->create([
            'post_content' => $searchTerm,
            'post_type'    => 'custom-post',
        ]);

        $this->factory->post->create_many(4, [
            'post_content' => $otherSearchTerm,
            'post_type'    => 'custom-post',
        ]);

        $query = new CustomPost();
        $query->search($searchTerm);

        $this->assertSame(1, $query->count());

        $query->reset();

        $query->search($otherSearchTerm);

        $this->assertSame(4, $query->count());
    }

    /** @test */
    public function can_set_posts_per_page(): void
    {
        $this->factory->post->create_many(9, [
            'post_type' => 'custom-post',
        ]);

        $query = new CustomPost();

        $originalNumberOfPages = $query->pages();

        $this->assertEquals(1, $originalNumberOfPages);

        $query->reset();
        $query->addArg('posts_per_page', 3);

        $this->assertNotSame($originalNumberOfPages, $query->pages());
    }
}
