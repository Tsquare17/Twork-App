<?php

/**
 * Define custom blade directives.
 *
 * @var \Jenssegers\Blade\Blade $blade
 */

$blade->directive('wpHeader', static function () {
    return '<?php get_header(); ?>';
});

$blade->directive('wpFooter', static function () {
    return '<?php get_footer(); ?>';
});

$blade->directive('wpPosts', static function () {
    return '<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>';
});

$blade->directive('endWpPosts', static function () {
    return '<?php endwhile; endif; ?>';
});

$blade->directive('wpTemplatePart', static function ($expression) {
    $parts = explode(',', $expression);

    if (isset($parts[1])) {
        return "<?php get_template_part( trim({$parts[0]}), trim({$parts[1]}) ); ?>";
    }
    return "<?php get_template_part( {$parts[0]} ); ?>";
});

$blade->directive('wpComments', static function () {
    return '<?php comments_template(); ?>';
});

$blade->directive('wpSidebar', static function () {
    return '<?php get_sidebar(); ?>';
});
