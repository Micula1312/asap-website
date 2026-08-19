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

    <nav class="site-nav" aria-label="Primary">
        <div class="menu-list">
            <a class="menu-link" href="<?php echo esc_url(is_front_page() ? '#about' : home_url('/#about')); ?>">About</a>
            <a class="menu-link" href="<?php echo esc_url(get_post_type_archive_link('work')); ?>">Works</a>
            <a class="menu-link" href="<?php echo esc_url(home_url('/ex-casa-del-custode')); ?>">Ex Casa del Custode</a>
            <a class="menu-link menu-link--radio" href="<?php echo esc_url(home_url('/radio')); ?>">Radio</a>
        </div>
    </nav>

    <button class="pill calendar-trigger" type="button" aria-haspopup="dialog" aria-controls="calendar-overlay" aria-expanded="false">Calendar</button>
</header>

<div class="calendar-overlay" id="calendar-overlay" role="dialog" aria-modal="true" aria-hidden="true" aria-label="ASAP calendar">
    <div class="calendar-overlay__top shell">
        <span class="calendar-overlay__title">Calendar</span>
        <button class="pill calendar-close" type="button">Close</button>
    </div>

    <div class="calendar-overlay__content shell">
        <?php
        $events = new WP_Query([
            'post_type' => 'event',
            'posts_per_page' => 20,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
        ]);
        ?>

        <?php if ($events->have_posts()) : ?>
            <div class="event-list">
                <?php while ($events->have_posts()) : $events->the_post(); ?>
                    <a class="event-row" href="<?php the_permalink(); ?>">
                        <span class="event-row__date"><?php echo esc_html(get_the_date('d.m.Y')); ?></span>
                        <span class="event-row__title"><?php the_title(); ?></span>
                        <span class="event-row__arrow" aria-hidden="true">↗</span>
                    </a>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p class="empty-state">No events yet.</p>
        <?php endif; ?>
    </div>
</div>
