<?php
get_header();

global $post;

?>

<main id="content homepage">
    <h1><?= the_title() ?></h1>
    <?= twork_nonce_input('twork_nonce') ?>
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

    <div class="custom-posts">
        <h2>Custom Posts</h2>

        <?php foreach ($post->customPosts->fetch() as $null) : ?>
            <h4><?php the_title() ?></h4>
        <?php endforeach ?>

        <?php $post->customPosts->pagination() ?>
    </div>
</main>

<?php get_footer() ?>
