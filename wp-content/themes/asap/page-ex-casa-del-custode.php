<?php get_header(); ?>

<main class="page-shell shell page-casa">
    <?php while (have_posts()) : the_post(); ?>
        <header class="page-head">
            <div class="page-kicker">space</div>
            <h1 class="page-title"><?php the_title(); ?></h1>
        </header>

        <article <?php post_class('page-body'); ?>>
            <div class="page-body__content entry-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>

    <section class="casa-programme">
        <div class="page-kicker">programme</div>
        <div class="casa-programme__content">
            <p class="casa-programme__intro">Uno spazio vissuto, curato e programmato da ASAP.</p>
            <a class="home-story__link" href="<?php echo esc_url(home_url('/calendar')); ?>">see the calendar ↗</a>
        </div>
    </section>
</main>

<?php get_footer(); ?>
