<?php get_header(); ?>

<main class="site-main">
    <header class="archive-head shell">
        <p class="eyebrow">ASAP archive</p>
        <h1 class="archive-head__title">Works</h1>

        <?php
        $terms = get_terms([
            'taxonomy' => 'work_type',
            'hide_empty' => true,
        ]);
        ?>

        <?php if (!is_wp_error($terms) && $terms) : ?>
            <nav class="archive-filters" aria-label="Work filters">
                <a class="pill" href="<?php echo esc_url(get_post_type_archive_link('work')); ?>">All</a>
                <?php foreach ($terms as $term) : ?>
                    <a class="pill" href="<?php echo esc_url(get_term_link($term)); ?>"><?php echo esc_html($term->name); ?></a>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>
    </header>

    <section class="shell">
        <?php if (have_posts()) : ?>
            <div class="archive-work-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <a class="work-card archive-work-card" href="<?php the_permalink(); ?>">
                        <div class="work-card__media">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('large'); ?>
                            <?php else : ?>
                                <div class="work-card__placeholder"></div>
                            <?php endif; ?>
                        </div>

                        <div class="work-card__meta">
                            <h2><?php the_title(); ?></h2>
                            <span><?php echo esc_html(get_the_date('Y')); ?></span>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>

            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p class="empty-state">No works yet.</p>
        <?php endif; ?>
    </section>
</main>

<?php get_footer(); ?>
