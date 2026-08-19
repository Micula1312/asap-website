<?php get_header(); ?>

<main class="single-work shell">
    <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
            <section class="single-work__hero">
                <div class="single-work__heading">
                    <p class="eyebrow">ASAP work · <?php echo esc_html(get_the_date('Y')); ?></p>
                    <h1 class="single-work__title"><?php the_title(); ?></h1>

                    <?php
                    $types = get_the_terms(get_the_ID(), 'work_type');
                    if ($types && !is_wp_error($types)) :
                    ?>
                        <div class="single-work__meta">
                            <?php echo esc_html(implode(' · ', wp_list_pluck($types, 'name'))); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="single-work__hero-media">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('full'); ?>
                    <?php endif; ?>
                </div>
            </section>

            <section class="single-work__body">
                <div class="single-work__content entry-content">
                    <?php the_content(); ?>
                </div>
            </section>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
