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
    </section>
</main>

<?php get_footer(); ?>
