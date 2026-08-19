<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="rainbow-bar" aria-hidden="true"></div>

<header class="site-header shell">
    <a class="site-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="ASAP home">ASAP</a>

    <nav class="site-nav site-nav--primary" aria-label="Explore ASAP">
        <div class="menu-list menu-list--primary">
            <a class="menu-link menu-link--plain" href="<?php echo esc_url(is_front_page() ? '#about' : home_url('/#about')); ?>">about</a>
            <a class="menu-link menu-link--plain" href="<?php echo esc_url(is_front_page() ? '#works' : home_url('/#works')); ?>">works</a>
        </div>
    </nav>

    <nav class="site-nav site-nav--utility" aria-label="ASAP spaces and programme">
        <div class="menu-list menu-list--utility">
            <a class="menu-link menu-link--pill" href="<?php echo esc_url(home_url('/ex-casa-del-custode')); ?>">Ex Casa del Custode</a>
            <a class="menu-link menu-link--pill" href="<?php echo esc_url(home_url('/calendar')); ?>">Calendar</a>
        </div>
    </nav>

    <a class="radio-badge" href="<?php echo esc_url(get_post_type_archive_link('radio') ?: home_url('/radio')); ?>" aria-label="ASAP Radio">Radio</a>
</header>
