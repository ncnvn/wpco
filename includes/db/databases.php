<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wp-test.local.com
 * @since      1.0.0
 *
 * @package    WPCO
 * @subpackage WPCO/includes/db
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    die;
}

/**
 * DB definition class used in plugin
 *
 * @since      1.0.0
 * @package    WPCO
 * @subpackage WPCO/includes/db
 * @author     NCN <nhunc.dev@gmail.com>
 */
class WPCO_Db
{

    /**
     * Initialize the wpco table
     *
     * @since    1.0.0
     */
    public static function generate()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . WPCO_TABLE;

        if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {
            $sql = "CREATE TABLE $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                option_group varchar(255) NOT NULL,
                option_group_order int(11) NULL,
                option_field longtext NULL,
                PRIMARY KEY (id)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }
}
