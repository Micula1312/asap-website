<?php get_header(); ?>

<?php
$home_video_id = (int) get_theme_mod('asap_home_video', 0);
$home_video_url = $home_video_id ? wp_get_attachment_url($home_video_id) : '';
$random_s_word = function_exists('asap_core_random_s_word') ? asap_core_random_s_word() : 'Soft';
$s_words = function_exists('asap_core_get_s_words') ? asap_core_get_s_words(18) : ['Soft', 'Strange', 'Sensitive', 'Slippery', 'Subversive', 'Slow', 'Shared'];
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
    </section>

    <section class="home-panel home-story shell" id="about">
        <div class="home-story__label">about</div>
        <div class="home-story__copy">
            <p>ASAP è un collettivo artistico e uno spazio di produzione condivisa.</p>
            <a class="home-story__link" href="<?php echo esc_url(home_url('/about')); ?>">more about us ↗</a>
        </div>
    </section>

    <section class="home-panel home-story shell" id="works">
        <div class="home-story__label">works</div>
        <div class="home-story__copy">
            <p>Costruiamo performance, installazioni, workshop, eventi e situazioni collettive.</p>
            <a class="home-story__link" href="<?php echo esc_url(get_post_type_archive_link('work')); ?>">explore the archive ↗</a>
        </div>
    </section>

    <section class="home-panel home-story shell" id="casa">
        <div class="home-story__label">space</div>
        <div class="home-story__copy">
            <p>Da un anno ASAP vive e cura lo spazio di Ex Casa del Custode.</p>
            <a class="home-story__link" href="<?php echo esc_url(home_url('/ex-casa-del-custode')); ?>">enter the space ↗</a>
        </div>
    </section>

    <section class="home-panel home-story shell" id="programme">
        <div class="home-story__label">programme</div>
        <div class="home-story__copy">
            <p>Segui quello che sta succedendo.</p>
            <div class="home-story__actions">
                <a class="home-story__link" href="<?php echo esc_url(home_url('/radio')); ?>">listen to the radio ↗</a>
                <button class="home-story__link home-story__link--button calendar-trigger-inline" type="button">see the calendar ↗</button>
            </div>
        </div>
    </section>

    <section class="home-panel home-sword shell" id="as-s-as-possible">
        <div class="home-sword__left">
            <div class="home-sword__phrase" aria-live="polite">
                As <span class="home-sword__word"><?php echo esc_html($random_s_word); ?></span><br>As Possible
            </div>

            <form class="home-sword__form" id="asap-s-word-form" data-endpoint="<?php echo esc_url(rest_url('asap/v1/s-word')); ?>">
                <label for="asap-s-word">your S?</label>
                <div class="home-sword__inputrow">
                    <span>As</span>
                    <input id="asap-s-word" name="word" type="text" maxlength="40" autocomplete="off" placeholder="Soft" required>
                    <span>As Possible</span>
                    <button type="submit">send ↗</button>
                </div>
                <p class="home-sword__feedback" aria-live="polite"></p>
            </form>
        </div>

        <aside class="home-sword__visualizer" aria-label="Collected S words">
            <div class="home-sword__visualizer-title">S words</div>
            <div class="home-sword__list" id="asap-s-word-list">
                <?php foreach ($s_words as $index => $word) : ?>
                    <div class="home-sword__list-item" style="--i: <?php echo esc_attr((string) $index); ?>">
                        <?php echo esc_html($word); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </aside>
    </section>
</main>

<?php get_footer(); ?>
