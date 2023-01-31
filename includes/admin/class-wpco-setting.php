<?php
/**
 * WPCO Setting group and field.
 *
 * @link  https://wp-test.local.com
 * @since 1.0.0
 *
 * @package    WPCO
 * @subpackage WPCO/includes/admin
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * WPCO Setting group and field
 *
 * @since      1.0.0
 * @package    WPCO
 * @subpackage WPCO/includes/admin
 * @author     NCN <nhunc.dev@gmail.com>
 */
class WPCO_Setting {

	/**
	 * Get data wpco-settings
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_data_wpco_settings() {
		global $wpdb;
		$table = $wpdb->prefix . WPCO_TABLE;

		$query = "SELECT * FROM {$table} ORDER BY option_group_order ASC";
		$data  = $wpdb->get_results( $query );

		return $data;
	}

}
