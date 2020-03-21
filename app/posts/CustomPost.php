<?php

namespace Twork\App\Posts;

use Twork\Admin\Posts\CustomPost as AbstractCustomPost;

class CustomPost extends AbstractCustomPost
{
    public function define()
    {
        $this->postType = 'custom-post';
        $this->name = 'Custom Posts';
    }
}
