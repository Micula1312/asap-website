<?php
/**
 * ASAP theme setup.
 */

if (!defined('ABSPATH')) {
    exit;
}

function asap_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    register_nav_menus([
        'primary' => __('Primary menu', 'asap'),
    ]);
}
add_action('after_setup_theme', 'asap_theme_setup');

function asap_enqueue_assets() {
    wp_enqueue_style(
        'asap-style',
        get_stylesheet_uri(),
        [],
        wp_get_theme()->get('Version')
    );

    wp_enqueue_style(
        'asap-main',
        get_template_directory_uri() . '/assets/css/main.css',
        ['asap-style'],
        wp_get_theme()->get('Version')
    );

    wp_enqueue_script(
        'asap-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('wp_enqueue_scripts', 'asap_enqueue_assets');

function asap_register_content_types() {
    $types = [
        'work' => ['Works', 'Work'],
        'event' => ['Events', 'Event'],
        'member' => ['Members', 'Member'],
        'news' => ['News', 'News'],
        'radio' => ['Radio', 'Radio'],
    ];

    foreach ($types as $slug => $labels) {
        register_post_type($slug, [
            'labels' => [
                'name' => __($labels[0], 'asap'),
                'singular_name' => __($labels[1], 'asap'),
                'add_new_item' => sprintf(__('Add %s', 'asap'), $labels[1]),
                'edit_item' => sprintf(__('Edit %s', 'asap'), $labels[1]),
            ],
            'public' => true,
            'show_in_rest' => true,
            'menu_position' => 20,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
            'has_archive' => in_array($slug, ['work', 'event', 'news', 'radio'], true),
            'rewrite' => ['slug' => $slug === 'work' ? 'works' : $slug],
        ]);
    }

    register_taxonomy('work_type', ['work'], [
        'labels' => [
            'name' => __('Work types', 'asap'),
            'singular_name' => __('Work type', 'asap'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => false,
        'rewrite' => ['slug' => 'work-type'],
    ]);
}
add_action('init', 'asap_register_content_types');

/**
 * Homepage media controls.
 * The video is uploaded from Appearance > Customize > ASAP Home.
 */
function asap_customize_register($wp_customize) {
    $wp_customize->add_section('asap_home', [
        'title' => __('ASAP Home', 'asap'),
        'priority' => 30,
    ]);

    $wp_customize->add_setting('asap_home_video', [
        'default' => 0,
        'sanitize_callback' => 'absint',
    ]);

    $wp_customize->add_control(new WP_Customize_Media_Control(
        $wp_customize,
        'asap_home_video',
        [
            'label' => __('Homepage fullscreen video', 'asap'),
            'section' => 'asap_home',
            'mime_type' => 'video',
        ]
    ));
}
add_action('customize_register', 'asap_customize_register');
