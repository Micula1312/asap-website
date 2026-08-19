<?php
if (!defined('ABSPATH')) {
    exit;
}

function asap_core_register_work_editorial_meta() {
    $fields = [
        'asap_description_rich' => 'wp_kses_post',
        'asap_work_kind' => 'sanitize_text_field',
        'asap_production_rich' => 'wp_kses_post',
        'asap_credits_rich' => 'wp_kses_post',
    ];

    foreach ($fields as $key => $sanitize) {
        register_post_meta('work', $key, [
            'single' => true,
            'show_in_rest' => true,
            'type' => 'string',
            'sanitize_callback' => $sanitize,
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            },
        ]);
    }
}
add_action('init', 'asap_core_register_work_editorial_meta');

function asap_core_work_editorial_metabox() {
    add_meta_box(
        'asap_work_editorial',
        __('Project editorial fields', 'asap-core'),
        'asap_core_render_work_editorial_metabox',
        'work',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'asap_core_work_editorial_metabox');

function asap_core_render_work_editorial_metabox($post) {
    wp_nonce_field('asap_save_work_editorial', 'asap_work_editorial_nonce');

    $description = get_post_meta($post->ID, 'asap_description_rich', true);
    $work_kind = get_post_meta($post->ID, 'asap_work_kind', true);
    $production = get_post_meta($post->ID, 'asap_production_rich', true);
    $credits = get_post_meta($post->ID, 'asap_credits_rich', true);
    ?>
    <div style="display:grid;gap:22px">
        <div>
            <label for="asap_work_kind"><strong><?php esc_html_e('Type of work / format', 'asap-core'); ?></strong></label>
            <input id="asap_work_kind" name="asap_work_kind" type="text" value="<?php echo esc_attr($work_kind); ?>" class="widefat" placeholder="multimedia performance, 40 min">
            <p class="description"><?php esc_html_e('Free editorial wording shown in the project sheet. Work Type taxonomy remains available for filters.', 'asap-core'); ?></p>
        </div>

        <div>
            <strong><?php esc_html_e('Description', 'asap-core'); ?></strong>
            <?php
            wp_editor($description, 'asap_description_rich_editor', [
                'textarea_name' => 'asap_description_rich',
                'textarea_rows' => 12,
                'media_buttons' => false,
                'teeny' => false,
                'quicktags' => true,
            ]);
            ?>
        </div>

        <div>
            <strong><?php esc_html_e('Production', 'asap-core'); ?></strong>
            <?php
            wp_editor($production, 'asap_production_rich_editor', [
                'textarea_name' => 'asap_production_rich',
                'textarea_rows' => 7,
                'media_buttons' => false,
                'teeny' => false,
                'quicktags' => true,
            ]);
            ?>
        </div>

        <div>
            <strong><?php esc_html_e('Credits', 'asap-core'); ?></strong>
            <?php
            wp_editor($credits, 'asap_credits_rich_editor', [
                'textarea_name' => 'asap_credits_rich',
                'textarea_rows' => 7,
                'media_buttons' => false,
                'teeny' => false,
                'quicktags' => true,
            ]);
            ?>
        </div>
    </div>
    <?php
}

function asap_core_save_work_editorial($post_id) {
    if (!isset($_POST['asap_work_editorial_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['asap_work_editorial_nonce'])), 'asap_save_work_editorial')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['asap_work_kind'])) {
        update_post_meta($post_id, 'asap_work_kind', sanitize_text_field(wp_unslash($_POST['asap_work_kind'])));
    }

    foreach (['asap_description_rich', 'asap_production_rich', 'asap_credits_rich'] as $key) {
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, wp_kses_post(wp_unslash($_POST[$key])));
        }
    }
}
add_action('save_post_work', 'asap_core_save_work_editorial', 20);
