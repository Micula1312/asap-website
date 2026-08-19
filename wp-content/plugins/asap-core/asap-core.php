<?php
/**
 * Plugin Name: ASAP Core
 * Description: Content model for ASAP APS: works, events, members, news, radio and CV entries.
 * Version: 0.1.0
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
        'asap_teaser_url' => ['type' => 'string', 'sanitize_callback' => 'esc_url_raw'],
        'asap_credits' => ['type' => 'string', 'sanitize_callback' => 'sanitize_textarea_field'],
        'asap_layout' => ['type' => 'string', 'sanitize_callback' => 'sanitize_key'],
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
