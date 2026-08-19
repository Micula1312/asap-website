<?php get_header(); ?>

<main class="single-work shell">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $year = get_post_meta(get_the_ID(), 'asap_year', true);
        $subtitle = get_post_meta(get_the_ID(), 'asap_subtitle', true);
        $format = get_post_meta(get_the_ID(), 'asap_format', true);
        $duration = get_post_meta(get_the_ID(), 'asap_duration', true);
        $teaser_url = get_post_meta(get_the_ID(), 'asap_teaser_url', true);
        $credits = get_post_meta(get_the_ID(), 'asap_credits', true);
        $types = get_the_terms(get_the_ID(), 'work_type');
        ?>

        <article <?php post_class('project-sheet'); ?>>
            <section class="project-sheet__left">
                <div class="project-sheet__brand">ASAPortfolio</div>

                <div class="project-sheet__copy">
                    <h1 class="project-sheet__title"><?php the_title(); ?></h1>

                    <?php if ($subtitle) : ?>
                        <p class="project-sheet__subtitle"><?php echo esc_html($subtitle); ?></p>
                    <?php endif; ?>

                    <div class="project-sheet__meta">
                        <?php if ($year) : ?><div><?php echo esc_html($year); ?></div><?php endif; ?>
                        <?php if ($format || $duration) : ?>
                            <div>
                                <?php echo esc_html($format); ?><?php echo ($format && $duration) ? ', ' : ''; ?><?php echo esc_html($duration); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($types && !is_wp_error($types)) : ?>
                            <div><?php echo esc_html(implode(' · ', wp_list_pluck($types, 'name'))); ?></div>
                        <?php endif; ?>

                        <?php if ($teaser_url) : ?>
                            <a href="<?php echo esc_url($teaser_url); ?>" target="_blank" rel="noopener">teaser ↗</a>
                        <?php endif; ?>
                    </div>

                    <div class="project-sheet__content entry-content">
                        <?php the_content(); ?>
                    </div>

                    <?php if ($credits) : ?>
                        <div class="project-sheet__credits">
                            <?php echo nl2br(esc_html($credits)); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <section class="project-sheet__right">
                <?php if (has_post_thumbnail()) : ?>
                    <figure class="project-sheet__hero">
                        <?php the_post_thumbnail('full'); ?>
                    </figure>
                <?php else : ?>
                    <div class="project-sheet__hero project-sheet__hero--empty"></div>
                <?php endif; ?>
            </section>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
