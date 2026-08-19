<?php
if (!defined('ABSPATH')) {
    exit;
}

function asap_core_event_metaboxes() {
    add_meta_box(
        'asap_event_programme',
        __('Programme details', 'asap-core'),
        'asap_core_render_event_programme',
        'event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'asap_core_event_metaboxes');

function asap_core_render_event_programme($post) {
    wp_nonce_field('asap_save_event_programme', 'asap_event_programme_nonce');

    $start = get_post_meta($post->ID, 'asap_event_start', true);
    $end = get_post_meta($post->ID, 'asap_event_end', true);
    $location = get_post_meta($post->ID, 'asap_event_location', true);
    $url = get_post_meta($post->ID, 'asap_event_url', true);
    $label = get_post_meta($post->ID, 'asap_event_label', true);
    $booking = get_post_meta($post->ID, 'asap_event_booking', true);
    ?>
    <style>
        .asap-event-fields{display:grid;grid-template-columns:1fr 1fr;gap:18px}.asap-event-field{display:flex;flex-direction:column;gap:6px}.asap-event-field--full{grid-column:1/-1}.asap-event-help{margin:4px 0 0;color:#646970}@media(max-width:782px){.asap-event-fields{grid-template-columns:1fr}.asap-event-field--full{grid-column:auto}}
    </style>
    <div class="asap-event-fields">
        <label class="asap-event-field">
            <strong><?php esc_html_e('Start', 'asap-core'); ?></strong>
            <input type="datetime-local" name="asap_event_start" value="<?php echo esc_attr($start); ?>">
        </label>
        <label class="asap-event-field">
            <strong><?php esc_html_e('End', 'asap-core'); ?></strong>
            <input type="datetime-local" name="asap_event_end" value="<?php echo esc_attr($end); ?>">
        </label>
        <label class="asap-event-field">
            <strong><?php esc_html_e('Location', 'asap-core'); ?></strong>
            <input type="text" name="asap_event_location" value="<?php echo esc_attr($location); ?>" placeholder="Ex Casa del Custode, Bologna">
        </label>
        <label class="asap-event-field">
            <strong><?php esc_html_e('Programme label', 'asap-core'); ?></strong>
            <input type="text" name="asap_event_label" value="<?php echo esc_attr($label); ?>" placeholder="performance / workshop / party / talk">
        </label>
        <label class="asap-event-field asap-event-field--full">
            <strong><?php esc_html_e('Event link', 'asap-core'); ?></strong>
            <input type="url" name="asap_event_url" value="<?php echo esc_attr($url); ?>" placeholder="https://…">
        </label>
        <label class="asap-event-field asap-event-field--full">
            <strong><?php esc_html_e('Booking / tickets link', 'asap-core'); ?></strong>
            <input type="url" name="asap_event_booking" value="<?php echo esc_attr($booking); ?>" placeholder="https://…">
            <span class="asap-event-help"><?php esc_html_e('Use the main editor for the full event description and the featured image for its visual.', 'asap-core'); ?></span>
        </label>
    </div>
    <?php
}

function asap_core_save_event_programme($post_id) {
    if (!isset($_POST['asap_event_programme_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['asap_event_programme_nonce'])), 'asap_save_event_programme')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $text_fields = ['asap_event_start', 'asap_event_end', 'asap_event_location', 'asap_event_label'];
    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field(wp_unslash($_POST[$field])));
        }
    }

    foreach (['asap_event_url', 'asap_event_booking'] as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, esc_url_raw(wp_unslash($_POST[$field])));
        }
    }
}
add_action('save_post_event', 'asap_core_save_event_programme');
