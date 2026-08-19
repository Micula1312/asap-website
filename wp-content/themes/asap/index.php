<?php get_header(); ?>

<main class="site-main shell">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('content-card'); ?>>
                <h1 class="display-title"><?php the_title(); ?></h1>
                <div class="entry-content"><?php the_content(); ?></div>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <p><?php esc_html_e('Nothing here yet.', 'asap'); ?></p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
