<?php

add_action('init', static function () {
    register_post_type('custom-post', [
        'labels' => [
            'name' => __('Custom Posts', 'twork'),
            'singular_name' => __('Custom Post', 'twork'),
        ]
    ]);
});
