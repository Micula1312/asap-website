<?php
if (!defined('ABSPATH')) {
    exit;
}

function asap_core_s_words_table() {
    global $wpdb;
    return $wpdb->prefix . 'asap_s_words';
}

function asap_core_install_s_words_table() {
    global $wpdb;
    $table = asap_core_s_words_table();
    $charset_collate = $wpdb->get_charset_collate();

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $sql = "CREATE TABLE {$table} (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        word varchar(60) NOT NULL,
        created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        approved tinyint(1) NOT NULL DEFAULT 1,
        PRIMARY KEY  (id),
        KEY approved (approved),
        KEY word (word)
    ) {$charset_collate};";

    dbDelta($sql);
}

function asap_core_ensure_s_words_table() {
    $installed = get_option('asap_s_words_db_version');
    if ($installed === '1') {
        return;
    }

    asap_core_install_s_words_table();
    update_option('asap_s_words_db_version', '1');
}
add_action('init', 'asap_core_ensure_s_words_table', 5);

function asap_core_random_s_word() {
    global $wpdb;
    $fallback = ['Soft', 'Strange', 'Sensitive', 'Slippery', 'Subversive', 'Slow', 'Shared'];
    $table = asap_core_s_words_table();

    $word = $wpdb->get_var("SELECT word FROM {$table} WHERE approved = 1 ORDER BY RAND() LIMIT 1");
    return $word ? $word : $fallback[array_rand($fallback)];
}

function asap_core_get_s_words($limit = 18) {
    global $wpdb;
    $table = asap_core_s_words_table();
    $limit = max(1, min(50, absint($limit)));

    $words = $wpdb->get_col(
        $wpdb->prepare(
            "SELECT word FROM {$table} WHERE approved = 1 ORDER BY created_at DESC, id DESC LIMIT %d",
            $limit
        )
    );

    if (!$words) {
        return ['Soft', 'Strange', 'Sensitive', 'Slippery', 'Subversive', 'Slow', 'Shared'];
    }

    return array_values(array_filter(array_map('sanitize_text_field', $words)));
}

function asap_core_register_s_word_route() {
    register_rest_route('asap/v1', '/s-word', [
        [
            'methods' => 'POST',
            'callback' => 'asap_core_submit_s_word',
            'permission_callback' => '__return_true',
        ],
        [
            'methods' => 'GET',
            'callback' => 'asap_core_list_s_words',
            'permission_callback' => '__return_true',
        ],
    ]);
}
add_action('rest_api_init', 'asap_core_register_s_word_route');

function asap_core_list_s_words(WP_REST_Request $request) {
    $limit = $request->get_param('limit');
    $limit = $limit ? absint($limit) : 18;

    return rest_ensure_response([
        'words' => asap_core_get_s_words($limit),
    ]);
}

function asap_core_submit_s_word(WP_REST_Request $request) {
    global $wpdb;

    $word = sanitize_text_field((string) $request->get_param('word'));
    $word = trim($word);

    if ($word === '' || mb_strlen($word) > 40) {
        return new WP_Error('invalid_word', __('Please enter a word up to 40 characters.', 'asap-core'), ['status' => 400]);
    }

    if (mb_strtolower(mb_substr($word, 0, 1)) !== 's') {
        return new WP_Error('not_s_word', __('The word has to start with S.', 'asap-core'), ['status' => 400]);
    }

    $word = mb_strtoupper(mb_substr($word, 0, 1)) . mb_substr($word, 1);

    $inserted = $wpdb->insert(
        asap_core_s_words_table(),
        [
            'word' => $word,
            'created_at' => current_time('mysql'),
            'approved' => 1,
        ],
        ['%s', '%s', '%d']
    );

    if (!$inserted) {
        return new WP_Error('save_failed', __('Could not save the word.', 'asap-core'), ['status' => 500]);
    }

    return rest_ensure_response([
        'saved' => true,
        'word' => $word,
    ]);
}
