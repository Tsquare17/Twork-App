<?php

namespace Twork\App\Posts;

use Twork\Admin\Posts\CustomPost as AbstractCustomPost;

/**
 * Class CustomPost
 * @package Twork\App\Posts
 */
class CustomPost extends AbstractCustomPost
{
    /**
     * Define the post type properties.
     *
     * @return void
     */
    public function define()
    {
        $this->postType = 'custom-post';
        $this->name = 'Custom Posts';
    }
}
