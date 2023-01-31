<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wp-test.local.com
 * @since             1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       WPCO Custom Options
 * Plugin URI:        https://wp-test.local.com
 * Description:       Add new custom options for WP
 * Version:           1.0.0
 * Author:            NCN
 * Author URI:        https://profiles.wordpress.org/nguyennhu/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpco
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

function wpco_create_table() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/db/class-wpco-db.php';
	WPCO_Db::generate();
}
register_activation_hook( __FILE__, 'wpco_create_table' );

// function gmt_allow_iframes_filter( $allowedposttags ) {

// Only change for users who can publish posts
// if ( !current_user_can( 'publish_posts' ) ) return $allowedposttags;

// Allow iframes and the following attributes
// $allowedposttags['iframe'] = array(
// 'align' => true,
// 'width' => true,
// 'height' => true,
// 'frameborder' => true,
// 'name' => true,
// 'src' => true,
// 'id' => true,
// 'class' => true,
// 'style' => true,
// 'scrolling' => true,
// 'marginwidth' => true,
// 'marginheight' => true,
// );

// return $allowedposttags;
// }
// add_filter( 'wp_kses_allowed_html', 'gmt_allow_iframes_filter' );
final class WPCO {


	/**
	 * Plugin version
	 *
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * Plugin setting key
	 *
	 * @var string
	 */
	public $setting_key = 'wpco';

	/**
	 * Instance of self
	 *
	 * @var WPCO
	 */
	private static $instance = null;

	/**
	 * Initializes the WPCO() class
	 *
	 * Checks for an existing WPCO() instance
	 * and if it doesn't find one, creates it.
	 */
	public static function init() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		$this->define_constants();

		$this->init_hooks();
		$this->includes();

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			new WPCO_Ajax();
		}
	}

	/**
	 * Initialize plugin for localization
	 *
	 * @uses load_plugin_textdomain()
	 */
	public function localization_setup() {
		load_plugin_textdomain( 'wpco', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Register the stylesheets
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'wpco-admin', plugin_dir_url( __FILE__ ) . 'assets/css/wpco-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		 wp_enqueue_script( 'wpco-admin', plugin_dir_url( __FILE__ ) . 'assets/js/wpco-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Define all constants
	 *
	 * @return void
	 */
	public function define_constants() {
		$this->define( 'WPCO_PLUGIN_VERSION', $this->version );
		$this->define( 'WPCO_TABLE', 'wpco' );
		$this->define( 'WPCO_FILE', __FILE__ );
		$this->define( 'WPCO_DIR', __DIR__ );
		$this->define( 'WPCO_DIR_URL', plugin_dir_url( __FILE__ ) );
		$this->define( 'WPCO_INC_DIR', plugin_dir_path( __FILE__ ) . '/includes' );
		$this->define( 'WPCO_TYPES', $this->type_list() );
		$this->define( 'WPCO_SETTING_KEY_GROUP', $this->setting_key );
	}

	/**
	 * Define constant if not already defined
	 *
	 * @since 2.9.16
	 *
	 * @param string      $name
	 * @param string|bool $value
	 *
	 * @return void
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Initialize the actions
	 *
	 * @return void
	 */
	public function init_hooks() {
		// Localize our plugin
		add_action( 'init', array( $this, 'localization_setup' ) );

		// initialize the classes
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'wpco_options' ) );
		add_action( 'admin_menu', array( $this, 'wpco_settings' ) );
	}

	/**
	 * Include all the required files
	 *
	 * @return void
	 */
	public function includes() {
		if ( is_admin() ) {
			require_once WPCO_INC_DIR . '/admin/class-wpco-ajax.php';
			require_once WPCO_INC_DIR . '/admin/class-wpco-options.php';
			require_once WPCO_INC_DIR . '/admin/class-wpco-setting.php';
		}
	}

	/**
	 * Call options
	 *
	 * @return void
	 */
	public function wpco_options() {
		add_menu_page(
			__( 'WPCO', 'wpco' ),
			__( 'WPCO', 'wpco' ),
			'manage_options',
			'wpco',
			function () {
				$options      = new WPCO_Options();
				$options_data = $options->get_data_wpco();
				include WPCO_DIR . '/templates/options.php';
			},
			'dashicons-list-view',
		);
	}

	/**
	 * Call settings
	 *
	 * @return void
	 */
	public function wpco_settings() {
		add_submenu_page(
			'wpco',
			__( 'Settings', 'wpco' ),
			__( 'Settings', 'wpco' ),
			'manage_options',
			'wpco-settings',
			function () {
				$settings      = new WPCO_Setting();
				$settings_data = $settings->get_data_wpco_settings();
				include WPCO_DIR . '/templates/settings.php';
			},
		);
	}

	/**
	 * List WPCO type
	 *
	 * @return void
	 */
	public function type_list() {
		$types = array(
			'text'     => __( 'Textbox', 'wpco' ),
			'textarea' => __( 'Textarea', 'wpco' ),
			// 'image' => __('Image', 'wpco'),
			// 'checkbox' => __('Checkbox', 'wpco'),
		);

		return $types;
	}
}

/**
 * Load WPCO Plugin when all plugins loaded
 *
 * @return WPCO
 */
function run_wpco() {
	return WPCO::init();
}

// Lets Go....
run_wpco();
