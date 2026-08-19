<?php get_header(); ?>

<main class="page-shell shell">
    <?php while (have_posts()) : the_post(); ?>
        <header class="page-head">
            <div class="page-kicker">ASAP</div>
            <h1 class="page-title"><?php the_title(); ?></h1>
        </header>

        <article <?php post_class('page-body'); ?>>
            <div class="page-body__content entry-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
