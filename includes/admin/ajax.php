<?php

/**
 * @link       https://wp-test.local.com
 * @since      1.0.0
 *
 * @package    WPCO
 * @subpackage WPCO/includes/admin
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    die;
}

/**
 * WPCO Ajax action
 *
 * @since      1.0.0
 * @package    WPCO
 * @subpackage WPCO/includes/admin
 * @author     NCN <nhunc.dev@gmail.com>
 */
class WPCO_Ajax
{
    /**
     * Class constructor
     *
     * @return void
     */
    public function __construct()
    {
        add_action('wp_ajax_wpco_save_settings', [$this, 'save_settings']);
        add_action('wp_ajax_nopriv_wpco_save_settings', [$this, 'save_settings']);

        add_action('wp_ajax_wpco_save_options', [$this, 'save_options']);
        add_action('wp_ajax_nopriv_wpco_save_options', [$this, 'save_options']);
    }

    /**
     * wpco_save_settings
     */
    public function save_settings($request)
    {
        if (!wp_verify_nonce(sanitize_key($_POST['_wpnonce']), WPCO_SETTING_KEY_GROUP)) {
            wp_send_json_error(__('Error', 'wpco'));
            wp_die(-1, 403);
        }

        $success = sprintf('<div class="alert alert-success">%s</div>', __('Successfully saved!', 'wpco'));

        global $wpdb;
        $table_name = $wpdb->prefix . WPCO_TABLE;

        $wpdb->query("TRUNCATE TABLE {$table_name}");

        if (isset($_POST['group']) && is_array($_POST['group'])) {
            $groupData = $_POST['group'];
            $groupData = stripslashes_deep($groupData);
            $groupData = array_values($groupData);

            if (!empty($groupData)) {
                foreach ($groupData as $group_id => $group) {
                    $order_number = !empty($group_id) ? absint($group_id) : 0;
                    $option_group = sanitize_text_field($group['group_name']);

                    $option_field = [];
                    if (isset($group['fields']) && is_array($group['fields'])) {
                        $option_field = $group['fields'];
                    }
                    $option_field = maybe_serialize($option_field);

                    $wpdb->insert($table_name, array(
                        'option_group' => $option_group,
                        'option_group_order' => $order_number,
                        'option_field' => $option_field
                    ));
                }
            }
        }

        wp_send_json_success($success);
    }

    /**
     * wpco_save_options
     */
    public function save_options()
    {
        if (!wp_verify_nonce(sanitize_key($_POST['_wpnonce']), WPCO_SETTING_KEY_GROUP)) {
            wp_send_json_error(__('Error', 'wpco'));
            wp_die(-1, 403);
        }

        $success = sprintf('<div class="alert alert-success">%s</div>', __('Successfully saved!', 'wpco'));

        if (isset($_POST['data']) && is_array($_POST['data'])) {
            $options = $_POST['data'];
            $options = stripslashes_deep($options);

            if (!empty($options)) {
                foreach ($options as $option => $value) {
                    $option = sanitize_text_field($option);
                    $value = maybe_serialize($value);

                    update_option($option, $value);
                }
            }
        }

        wp_send_json_success($success);
    }
}
