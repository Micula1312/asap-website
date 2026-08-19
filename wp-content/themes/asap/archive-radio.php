<?php get_header(); ?>

<main class="site-main shell radio-page">
    <header class="archive-head radio-page__head">
        <p class="eyebrow">ASAP radio</p>
        <h1 class="archive-head__title">Radio</h1>
        <p class="radio-page__intro">Live, podcast e archivio sonoro di ASAP.</p>
    </header>

    <section class="radio-page__list">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php $types = get_the_terms(get_the_ID(), 'radio_type'); ?>
                <a class="radio-row" href="<?php the_permalink(); ?>">
                    <span class="radio-row__type">
                        <?php echo ($types && !is_wp_error($types)) ? esc_html(implode(' · ', wp_list_pluck($types, 'name'))) : 'radio'; ?>
                    </span>
                    <span class="radio-row__title"><?php the_title(); ?></span>
                    <span class="radio-row__date"><?php echo esc_html(get_the_date('Y')); ?></span>
                    <span aria-hidden="true">↗</span>
                </a>
            <?php endwhile; ?>
        <?php else : ?>
            <p class="empty-state">Radio coming soon.</p>
        <?php endif; ?>
    </section>
</main>

<?php get_footer(); ?>
