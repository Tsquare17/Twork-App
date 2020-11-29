<?php
get_header();

global $twork;

?>

<main id="content homepage">
    <h1><?= $twork->title ?></h1>
    <div class="posts">
        <h2>Posts</h2>
        <?php if (have_posts()) : ?>
            <?php
            while (have_posts()) :
                the_post();
                ?>

                <div><?php the_title() ?></div>

                <?php
            endwhile;
        endif;
        ?>
    </div>
</main>

<?php get_footer() ?>
