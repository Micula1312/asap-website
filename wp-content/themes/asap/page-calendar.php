<?php
/* Template Name: ASAP Calendar */
get_header();

$now = current_time('Y-m-d\TH:i');

$upcoming = new WP_Query([
    'post_type' => 'event',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'meta_key' => 'asap_event_start',
    'meta_value' => $now,
    'meta_compare' => '>=',
    'orderby' => 'meta_value',
    'order' => 'ASC',
]);

$past = new WP_Query([
    'post_type' => 'event',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'meta_key' => 'asap_event_start',
    'meta_value' => $now,
    'meta_compare' => '<',
    'orderby' => 'meta_value',
    'order' => 'DESC',
]);

function asap_calendar_event_row() {
    $start = get_post_meta(get_the_ID(), 'asap_event_start', true);
    $location = get_post_meta(get_the_ID(), 'asap_event_location', true);
    $label = get_post_meta(get_the_ID(), 'asap_event_label', true);
    $booking = get_post_meta(get_the_ID(), 'asap_event_booking', true);
    $date_label = $start ? wp_date('d.m.Y · H:i', strtotime($start)) : get_the_date('d.m.Y');
    ?>
    <article class="calendar-event">
        <a class="event-row calendar-page__row" href="<?php the_permalink(); ?>">
            <span class="event-row__date"><?php echo esc_html($date_label); ?></span>
            <span class="event-row__title"><?php the_title(); ?></span>
            <span class="calendar-page__location"><?php echo esc_html($location); ?></span>
            <span class="event-row__arrow" aria-hidden="true">↗</span>
        </a>
        <?php if ($label || $booking) : ?>
            <div class="calendar-event__meta">
                <?php if ($label) : ?><span><?php echo esc_html($label); ?></span><?php endif; ?>
                <?php if ($booking) : ?><a href="<?php echo esc_url($booking); ?>" target="_blank" rel="noopener noreferrer">booking / tickets ↗</a><?php endif; ?>
            </div>
        <?php endif; ?>
    </article>
    <?php
}
?>

<main class="site-main shell calendar-page">
    <header class="archive-head calendar-page__head">
        <p class="eyebrow">programme</p>
        <h1 class="archive-head__title">Calendar</h1>
    </header>

    <section class="calendar-page__events calendar-page__events--upcoming">
        <div class="calendar-page__section-head">
            <span>upcoming</span>
            <span><?php echo esc_html((string) $upcoming->found_posts); ?> events</span>
        </div>

        <?php if ($upcoming->have_posts()) : ?>
            <?php while ($upcoming->have_posts()) : $upcoming->the_post(); ?>
                <?php asap_calendar_event_row(); ?>
            <?php endwhile; wp_reset_postdata(); ?>
        <?php else : ?>
            <p class="empty-state">No upcoming events yet.</p>
        <?php endif; ?>
    </section>

    <?php if ($past->have_posts()) : ?>
        <section class="calendar-page__events calendar-page__events--past">
            <div class="calendar-page__section-head">
                <span>past programme</span>
                <span><?php echo esc_html((string) $past->found_posts); ?> events</span>
            </div>

            <?php while ($past->have_posts()) : $past->the_post(); ?>
                <?php asap_calendar_event_row(); ?>
            <?php endwhile; wp_reset_postdata(); ?>
        </section>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
