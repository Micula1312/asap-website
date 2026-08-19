<?php
/**
 * Plugin Name: ASAP Core
 * Description: Content model for ASAP APS: works, events, members, news, radio and CV entries.
 * Version: 0.2.0
 * Author: ASAP / Micol Gelsi
 * Text Domain: asap-core
 */

if (!defined('ABSPATH')) {
    exit;
}

function asap_core_register_post_types() {
    $types = [
        'work' => [
            'plural' => 'Works',
            'singular' => 'Work',
            'slug' => 'works',
            'archive' => true,
            'icon' => 'dashicons-art',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields'],
        ],
        'event' => [
            'plural' => 'Events',
            'singular' => 'Event',
            'slug' => 'events',
            'archive' => true,
            'icon' => 'dashicons-calendar-alt',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields'],
        ],
        'member' => [
            'plural' => 'Members',
            'singular' => 'Member',
            'slug' => 'team',
            'archive' => false,
            'icon' => 'dashicons-groups',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields'],
        ],
        'news' => [
            'plural' => 'News',
            'singular' => 'News item',
            'slug' => 'news',
            'archive' => true,
            'icon' => 'dashicons-megaphone',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields'],
        ],
        'radio' => [
            'plural' => 'Radio',
            'singular' => 'Radio item',
            'slug' => 'radio',
            'archive' => true,
            'icon' => 'dashicons-format-audio',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields'],
        ],
        'cv_item' => [
            'plural' => 'CV',
            'singular' => 'CV item',
            'slug' => 'cv-item',
            'archive' => false,
            'icon' => 'dashicons-id-alt',
            'supports' => ['title', 'editor', 'revisions', 'custom-fields'],
        ],
    ];

    foreach ($types as $post_type => $config) {
        register_post_type($post_type, [
            'labels' => [
                'name' => __($config['plural'], 'asap-core'),
                'singular_name' => __($config['singular'], 'asap-core'),
                'add_new_item' => sprintf(__('Add %s', 'asap-core'), $config['singular']),
                'edit_item' => sprintf(__('Edit %s', 'asap-core'), $config['singular']),
                'new_item' => sprintf(__('New %s', 'asap-core'), $config['singular']),
                'view_item' => sprintf(__('View %s', 'asap-core'), $config['singular']),
                'search_items' => sprintf(__('Search %s', 'asap-core'), $config['plural']),
            ],
            'public' => true,
            'show_in_rest' => true,
            'menu_icon' => $config['icon'],
            'menu_position' => 20,
            'supports' => $config['supports'],
            'has_archive' => $config['archive'],
            'rewrite' => ['slug' => $config['slug'], 'with_front' => false],
            'show_in_nav_menus' => true,
        ]);
    }
}
add_action('init', 'asap_core_register_post_types');

function asap_core_register_taxonomies() {
    register_taxonomy('work_type', ['work'], [
        'labels' => [
            'name' => __('Work types', 'asap-core'),
            'singular_name' => __('Work type', 'asap-core'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'rewrite' => ['slug' => 'work-type', 'with_front' => false],
    ]);

    register_taxonomy('cv_section', ['cv_item'], [
        'labels' => [
            'name' => __('CV sections', 'asap-core'),
            'singular_name' => __('CV section', 'asap-core'),
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
    ]);

    register_taxonomy('radio_type', ['radio'], [
        'labels' => [
            'name' => __('Radio types', 'asap-core'),
            'singular_name' => __('Radio type', 'asap-core'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'rewrite' => ['slug' => 'radio-type', 'with_front' => false],
    ]);
}
add_action('init', 'asap_core_register_taxonomies');

function asap_core_register_meta() {
    $work_fields = [
        'asap_year' => ['type' => 'string', 'sanitize_callback' => 'sanitize_text_field'],
        'asap_subtitle' => ['type' => 'string', 'sanitize_callback' => 'sanitize_text_field'],
        'asap_format' => ['type' => 'string', 'sanitize_callback' => 'sanitize_text_field'],
        'asap_duration' => ['type' => 'string', 'sanitize_callback' => 'sanitize_text_field'],
        'asap_project_url' => ['type' => 'string', 'sanitize_callback' => 'esc_url_raw'],
        'asap_teaser_url' => ['type' => 'string', 'sanitize_callback' => 'esc_url_raw'],
        'asap_credits' => ['type' => 'string', 'sanitize_callback' => 'sanitize_textarea_field'],
        'asap_layout' => ['type' => 'string', 'sanitize_callback' => 'sanitize_key'],
        'asap_gallery' => ['type' => 'string', 'sanitize_callback' => 'sanitize_text_field'],
    ];

    foreach ($work_fields as $key => $args) {
        register_post_meta('work', $key, [
            'single' => true,
            'show_in_rest' => true,
            'type' => $args['type'],
            'sanitize_callback' => $args['sanitize_callback'],
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            },
        ]);
    }

    $event_fields = [
        'asap_event_start' => 'sanitize_text_field',
        'asap_event_end' => 'sanitize_text_field',
        'asap_event_location' => 'sanitize_text_field',
        'asap_event_url' => 'esc_url_raw',
        'asap_event_label' => 'sanitize_text_field',
        'asap_event_booking' => 'esc_url_raw',
    ];

    foreach ($event_fields as $key => $sanitize) {
        register_post_meta('event', $key, [
            'single' => true,
            'show_in_rest' => true,
            'type' => 'string',
            'sanitize_callback' => $sanitize,
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            },
        ]);
    }

    $member_fields = [
        'asap_member_role' => 'sanitize_text_field',
        'asap_member_location' => 'sanitize_text_field',
        'asap_member_website' => 'esc_url_raw',
        'asap_member_instagram' => 'sanitize_text_field',
    ];

    foreach ($member_fields as $key => $sanitize) {
        register_post_meta('member', $key, [
            'single' => true,
            'show_in_rest' => true,
            'type' => 'string',
            'sanitize_callback' => $sanitize,
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            },
        ]);
    }

    $cv_fields = [
        'asap_cv_year' => 'sanitize_text_field',
        'asap_cv_place' => 'sanitize_text_field',
        'asap_cv_city' => 'sanitize_text_field',
        'asap_cv_url' => 'esc_url_raw',
        'asap_cv_member_id' => 'absint',
    ];

    foreach ($cv_fields as $key => $sanitize) {
        register_post_meta('cv_item', $key, [
            'single' => true,
            'show_in_rest' => true,
            'type' => $key === 'asap_cv_member_id' ? 'integer' : 'string',
            'sanitize_callback' => $sanitize,
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            },
        ]);
    }
}
add_action('init', 'asap_core_register_meta');

function asap_core_work_metaboxes() {
    add_meta_box(
        'asap_work_details',
        __('Project details', 'asap-core'),
        'asap_core_render_work_details',
        'work',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'asap_core_work_metaboxes');

function asap_core_render_work_details($post) {
    wp_nonce_field('asap_save_work_details', 'asap_work_details_nonce');

    $year = get_post_meta($post->ID, 'asap_year', true);
    $url = get_post_meta($post->ID, 'asap_project_url', true);
    $gallery = get_post_meta($post->ID, 'asap_gallery', true);
    $gallery_ids = array_filter(array_map('absint', explode(',', (string) $gallery)));
    ?>
    <style>
        .asap-fields{display:grid;grid-template-columns:1fr 1fr;gap:18px}.asap-field{display:flex;flex-direction:column;gap:6px}.asap-field--full{grid-column:1/-1}.asap-gallery-preview{display:flex;flex-wrap:wrap;gap:8px;margin:10px 0}.asap-gallery-preview img{width:92px;height:92px;object-fit:cover;border:1px solid #ccd0d4}.asap-help{color:#646970;margin:4px 0 0}
        @media(max-width:782px){.asap-fields{grid-template-columns:1fr}.asap-field--full{grid-column:auto}}
    </style>
    <div class="asap-fields">
        <label class="asap-field">
            <strong><?php esc_html_e('Year', 'asap-core'); ?></strong>
            <input type="text" name="asap_year" value="<?php echo esc_attr($year); ?>" placeholder="2026">
        </label>

        <label class="asap-field">
            <strong><?php esc_html_e('Project link', 'asap-core'); ?></strong>
            <input type="url" name="asap_project_url" value="<?php echo esc_attr($url); ?>" placeholder="https://…">
        </label>

        <div class="asap-field asap-field--full">
            <strong><?php esc_html_e('Gallery', 'asap-core'); ?></strong>
            <input type="hidden" id="asap_gallery" name="asap_gallery" value="<?php echo esc_attr($gallery); ?>">
            <div class="asap-gallery-preview" id="asap-gallery-preview">
                <?php foreach ($gallery_ids as $attachment_id) : ?>
                    <?php echo wp_get_attachment_image($attachment_id, 'thumbnail'); ?>
                <?php endforeach; ?>
            </div>
            <div>
                <button type="button" class="button" id="asap-gallery-select"><?php esc_html_e('Select / edit gallery', 'asap-core'); ?></button>
                <button type="button" class="button-link-delete" id="asap-gallery-clear" style="margin-left:10px"><?php esc_html_e('Clear gallery', 'asap-core'); ?></button>
            </div>
            <p class="asap-help"><?php esc_html_e('Project type is selected from the Work types panel. Rich description, production and credits are available in the editorial fields box.', 'asap-core'); ?></p>
        </div>
    </div>
    <?php
}

function asap_core_save_work_details($post_id) {
    if (!isset($_POST['asap_work_details_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['asap_work_details_nonce'])), 'asap_save_work_details')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['asap_year'])) {
        update_post_meta($post_id, 'asap_year', sanitize_text_field(wp_unslash($_POST['asap_year'])));
    }
    if (isset($_POST['asap_project_url'])) {
        update_post_meta($post_id, 'asap_project_url', esc_url_raw(wp_unslash($_POST['asap_project_url'])));
    }
    if (isset($_POST['asap_gallery'])) {
        $ids = array_filter(array_map('absint', explode(',', wp_unslash($_POST['asap_gallery']))));
        update_post_meta($post_id, 'asap_gallery', implode(',', $ids));
    }
}
add_action('save_post_work', 'asap_core_save_work_details');

function asap_core_admin_assets($hook) {
    global $post_type;
    if (!in_array($hook, ['post.php', 'post-new.php'], true) || $post_type !== 'work') {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script(
        'asap-core-admin',
        plugin_dir_url(__FILE__) . 'assets/admin.js',
        ['jquery'],
        '0.2.0',
        true
    );
}
add_action('admin_enqueue_scripts', 'asap_core_admin_assets');

function asap_core_seed_terms() {
    asap_core_register_taxonomies();

    foreach (['Performance', 'Workshop', 'Event', 'Installation', 'Research', 'Curatorial project'] as $term) {
        if (!term_exists($term, 'work_type')) {
            wp_insert_term($term, 'work_type');
        }
    }

    foreach (['Education', 'Exhibitions', 'Performances', 'Residencies', 'Awards', 'Teaching', 'Talks', 'Publications'] as $term) {
        if (!term_exists($term, 'cv_section')) {
            wp_insert_term($term, 'cv_section');
        }
    }

    foreach (['Live', 'Podcast', 'Archive'] as $term) {
        if (!term_exists($term, 'radio_type')) {
            wp_insert_term($term, 'radio_type');
        }
    }

    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'asap_core_seed_terms');

function asap_core_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'asap_core_deactivate');

require_once __DIR__ . '/includes/work-editorial-fields.php';
require_once __DIR__ . '/includes/event-fields.php';
require_once __DIR__ . '/includes/s-words.php';
