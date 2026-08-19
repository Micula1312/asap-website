<?php
/* Template Name: ASAP Calendar */
get_header();
?>

<main class="site-main shell calendar-page">
    <header class="archive-head calendar-page__head">
        <p class="eyebrow">programme</p>
        <h1 class="archive-head__title">Calendar</h1>
    </header>

    <?php
    $events = new WP_Query([
        'post_type' => 'event',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_key' => 'asap_event_start',
        'orderby' => [
            'meta_value' => 'ASC',
            'date' => 'ASC',
        ],
    ]);
    ?>

    <section class="calendar-page__events">
        <?php if ($events->have_posts()) : ?>
            <?php while ($events->have_posts()) : $events->the_post(); ?>
                <?php
                $start = get_post_meta(get_the_ID(), 'asap_event_start', true);
                $location = get_post_meta(get_the_ID(), 'asap_event_location', true);
                ?>
                <a class="event-row calendar-page__row" href="<?php the_permalink(); ?>">
                    <span class="event-row__date"><?php echo esc_html($start ?: get_the_date('d.m.Y')); ?></span>
                    <span class="event-row__title"><?php the_title(); ?></span>
                    <span class="calendar-page__location"><?php echo esc_html($location); ?></span>
                    <span class="event-row__arrow" aria-hidden="true">↗</span>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
        <?php else : ?>
            <p class="empty-state">No events yet.</p>
        <?php endif; ?>
    </section>
</main>

<?php get_footer(); ?>
