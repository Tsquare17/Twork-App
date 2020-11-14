<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header id="header">
    <div id="branding">
        <div id="site-title">
            <?= is_front_page() || is_home() ? '<h1>' : '' ?>
            <a href="<?= esc_url(home_url('/')) ?>" title="<?= esc_html(get_bloginfo('name')) ?>" rel="home">
                <?= esc_html(get_bloginfo('name')) ?>
            </a>
            <?= is_front_page() || is_home() ? '</h1>' : '' ?>
        </div>
    </div>
    <?php include 'nav.php' ?>
</header>
