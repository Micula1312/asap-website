<?php get_header(); ?>

<main class="page-shell shell page-about">
    <?php while (have_posts()) : the_post(); ?>
        <header class="page-head">
            <div class="page-kicker">about</div>
            <h1 class="page-title"><?php the_title(); ?></h1>
        </header>

        <article <?php post_class('page-body'); ?>>
            <div class="page-body__content entry-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>

    <?php
    $members = new WP_Query([
        'post_type' => 'member',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'menu_order title',
        'order' => 'ASC',
    ]);
    ?>

    <?php if ($members->have_posts()) : ?>
        <section class="about-team">
            <div class="page-kicker">team</div>
            <div class="about-team__grid">
                <?php while ($members->have_posts()) : $members->the_post(); ?>
                    <article class="about-member">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="about-member__image"><?php the_post_thumbnail('large'); ?></div>
                        <?php endif; ?>
                        <h2 class="about-member__name"><?php the_title(); ?></h2>
                        <?php $role = get_post_meta(get_the_ID(), 'asap_member_role', true); ?>
                        <?php if ($role) : ?><div class="about-member__role"><?php echo esc_html($role); ?></div><?php endif; ?>
                    </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
