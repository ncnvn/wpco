<?php
/**
 * Ajax POST action.
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
 * WPCO Ajax action
 *
 * @since      1.0.0
 * @package    WPCO
 * @subpackage WPCO/includes/admin
 * @author     NCN <nhunc.dev@gmail.com>
 */
class WPCO_Ajax {

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_ajax_wpco_save_settings', array( $this, 'save_settings' ) );
		add_action( 'wp_ajax_nopriv_wpco_save_settings', array( $this, 'save_settings' ) );

		add_action( 'wp_ajax_wpco_save_options', array( $this, 'save_options' ) );
		add_action( 'wp_ajax_nopriv_wpco_save_options', array( $this, 'save_options' ) );
	}

	/**
	 * WPCO_save_settings
	 */
	public function save_settings() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), WPCO_SETTING_KEY_GROUP ) ) {
			wp_send_json_error( __( 'Error', 'wpco' ) );
			wp_die( -1, 403 );
		}

		$success = sprintf( '<div class="alert alert-success">%s</div>', __( 'Successfully saved!', 'wpco' ) );

		global $wpdb;
		$table_name = $wpdb->prefix . WPCO_TABLE;

		$wpdb->query( "TRUNCATE TABLE {$table_name}" );

		$groups = isset( $_POST['group'] ) ? wp_unslash( $_POST['group'] ) : array();

		if ( ! empty( $groups ) ) {
			$groups = array_values( $groups );

			if ( ! empty( $groups ) ) {
				foreach ( $groups as $key => $group ) {
					$option_group = sanitize_text_field( $group['group_name'] );

					$fields       = isset( $group['fields'] ) ? wp_unslash( $group['fields'] ) : array();
					$option_field = maybe_serialize( $fields );

					$wpdb->insert(
						$table_name,
						array(
							'option_group'       => $option_group,
							'option_group_order' => $key,
							'option_field'       => $option_field,
						)
					);
				}
			}
		}

		wp_send_json_success( $success );
	}

	/**
	 * WPCO_save_options
	 */
	public function save_options() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), WPCO_SETTING_KEY_GROUP ) ) {
			wp_send_json_error( __( 'Error', 'wpco' ) );
			wp_die( -1, 403 );
		}

		$success = sprintf( '<div class="alert alert-success">%s</div>', __( 'Successfully saved!', 'wpco' ) );

		$options = isset( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : array();

		if ( ! empty( $options ) ) {
			foreach ( $options as $option => $value ) {
				$option = sanitize_text_field( $option );

				// This allows us to embed videopress videos into the release post.
				add_filter( 'wp_kses_allowed_html', array( $this, 'allow_post_embed_iframe' ), 10, 2 );
				$value = wp_kses_post( $value );
				remove_filter( 'wp_kses_allowed_html', array( $this, 'allow_post_embed_iframe' ), 10, 2 );

				update_option( $option, $value );
			}
		}

		wp_send_json_success( $success );
	}

	/**
	 * Temporarily allow post content to contain iframes, e.g. for videopress.
	 *
	 * @param string $tags    The tags.
	 * @param string $context The context.
	 */
	public function allow_post_embed_iframe( $tags, $context ) {
		if ( 'post' === $context ) {
			$tags['iframe'] = array(
				'src'             => true,
				'height'          => true,
				'width'           => true,
				'frameborder'     => true,
				'allowfullscreen' => true,
				'title'           => true,
				'allow'           => true,
				'loading'         => true,
				'referrerpolicy'  => true,
				'style'           => true,
			);
		}

		return $tags;
	}
}
