<?php get_header(); ?>

<?php
$home_video_id = (int) get_theme_mod('asap_home_video', 0);
$home_video_url = $home_video_id ? wp_get_attachment_url($home_video_id) : '';
?>

<main class="home-main">
    <section class="home-panel home-video" id="home-video" aria-label="ASAP intro">
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

        <button class="home-video__scroll" type="button" data-scroll-target="#about" aria-label="Go to About">
            <span>About</span><span aria-hidden="true">↓</span>
        </button>
    </section>

    <section class="home-panel home-story home-story--about shell" id="about">
        <div class="home-story__label">About</div>
        <div class="home-story__copy">
            <p>ASAP è un collettivo artistico e uno spazio di produzione condivisa.</p>
            <p>Lavoriamo tra performance, installazione, ricerca, pratiche partecipative e cultura indipendente.</p>
        </div>
    </section>

    <section class="home-panel home-story home-story--practice shell" id="practice">
        <div class="home-story__label">Practice</div>
        <div class="home-story__copy">
            <p>Costruiamo progetti a partire dai corpi, dalle relazioni e dai luoghi.</p>
            <p>Ogni formato può cambiare: performance, workshop, eventi, immagini, suono, festa, ricerca.</p>
        </div>
    </section>

    <section class="home-panel home-story home-story--space shell" id="space">
        <div class="home-story__label">ASAP</div>
        <div class="home-story__copy">
            <p>ASAP è anche una rete: persone, collaborazioni, produzioni e spazi che si attivano insieme.</p>
            <p>Il sito raccoglie ciò che facciamo e ciò che sta per succedere.</p>
        </div>
    </section>
</main>

<?php get_footer(); ?>
