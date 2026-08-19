<?php get_header(); ?>

<?php
$home_video_id = (int) get_theme_mod('asap_home_video', 0);
$home_video_url = $home_video_id ? wp_get_attachment_url($home_video_id) : '';
?>

<main class="home-main">
    <section class="home-video" id="home-video" aria-label="ASAP intro">
        <?php if ($home_video_url) : ?>
            <video class="home-video__media" autoplay muted loop playsinline preload="metadata">
                <source src="<?php echo esc_url($home_video_url); ?>">
            </video>
        <?php else : ?>
            <div class="home-video__fallback" aria-hidden="true">
                <span class="glow glow--1"></span>
                <span class="glow glow--2"></span>
                <span class="glow glow--3"></span>
            </div>
        <?php endif; ?>

        <button class="home-video__scroll" type="button" data-scroll-target="#home-head" aria-label="Scroll to ASAP introduction">
            <span>scroll</span>
            <span aria-hidden="true">↓</span>
        </button>
    </section>

    <section class="home-head shell" id="home-head">
        <div class="home-head__statement">
            <p class="eyebrow">ASAP APS · Bologna</p>
            <h1>As Soon As Possible.<br>As Strange As Possible.<br>As Softly As Possible.</h1>
        </div>

        <div class="home-head__intro">
            <p>ASAP è un collettivo artistico che sviluppa progetti performativi, installativi e partecipativi tra corpo, spazio, ecologia e pratiche multimediali.</p>

            <nav class="home-head__links" aria-label="Explore ASAP">
                <a class="pill" href="<?php echo esc_url(home_url('/about')); ?>">About</a>
                <a class="pill" href="<?php echo esc_url(get_post_type_archive_link('work')); ?>">Works</a>
                <a class="pill" href="<?php echo esc_url(home_url('/ex-casa-del-custode')); ?>">Ex Casa del Custode</a>
                <a class="pill" href="<?php echo esc_url(home_url('/radio')); ?>">Radio</a>
            </nav>
        </div>
    </section>

    <section class="home-section shell">
        <div class="section-heading">
            <h2>Selected works</h2>
            <a class="pill" href="<?php echo esc_url(get_post_type_archive_link('work')); ?>">All works</a>
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
