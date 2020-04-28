<?php

namespace Twork\App\Posts;

use Twork\Admin\Posts\CustomPost as AbstractCustomPost;

/**
 * Class CustomPost
 * @package Twork\App\Posts
 */
class CustomPost extends AbstractCustomPost
{
	protected $postType = 'custom-post';
	protected $name = 'Custom Posts';
}
