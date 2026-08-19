<?php get_header(); ?>

<main class="home-main">
    <section class="home-hero shell">
        <div class="home-hero__copy">
            <p class="eyebrow">ASAP APS · Bologna</p>
            <h1 class="home-title">As Soon As Possible.<br>As Strange As Possible.<br>As Softly As Possible.</h1>
            <p class="home-intro">
                ASAP è un collettivo artistico che sviluppa progetti performativi, installativi e partecipativi tra corpo, spazio, ecologia e pratiche multimediali.
            </p>
        </div>

        <div class="home-hero__visual" aria-hidden="true">
            <span class="glow glow--1"></span>
            <span class="glow glow--2"></span>
            <span class="glow glow--3"></span>
        </div>
    </section>

    <section class="home-section shell">
        <div class="section-heading">
            <h2>Selected works</h2>
            <a class="pill" href="<?php echo esc_url(home_url('/works')); ?>">All works</a>
        </div>

        <?php
        $works = new WP_Query([
            'post_type' => 'work',
            'posts_per_page' => 6,
            'post_status' => 'publish',
        ]);
        ?>

        <?php if ($works->have_posts()) : ?>
            <div class="work-grid">
                <?php while ($works->have_posts()) : $works->the_post(); ?>
                    <a class="work-card" href="<?php the_permalink(); ?>">
                        <div class="work-card__media">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('large'); ?>
                            <?php else : ?>
                                <div class="work-card__placeholder"></div>
                            <?php endif; ?>
                        </div>
                        <div class="work-card__meta">
                            <h3><?php the_title(); ?></h3>
                            <span><?php echo esc_html(get_the_date('Y')); ?></span>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <div class="work-grid work-grid--placeholder">
                <?php foreach (['Abundance', 'EXIT', 'Archive of the Untamed'] as $placeholder) : ?>
                    <article class="work-card">
                        <div class="work-card__media work-card__placeholder"></div>
                        <div class="work-card__meta"><h3><?php echo esc_html($placeholder); ?></h3><span>ASAP</span></div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="home-section shell home-news">
        <div class="section-heading"><h2>News</h2></div>
        <?php
        $news = new WP_Query([
            'post_type' => 'news',
            'posts_per_page' => 3,
            'post_status' => 'publish',
        ]);
        ?>
        <div class="news-list">
            <?php if ($news->have_posts()) : ?>
                <?php while ($news->have_posts()) : $news->the_post(); ?>
                    <a class="news-row" href="<?php the_permalink(); ?>">
                        <span><?php echo esc_html(get_the_date('d.m.Y')); ?></span>
                        <strong><?php the_title(); ?></strong>
                        <span>↗</span>
                    </a>
                <?php endwhile; wp_reset_postdata(); ?>
            <?php else : ?>
                <p class="empty-state">News coming soon.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
