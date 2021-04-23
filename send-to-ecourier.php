<?php
/**
 * Plugin Name:     Send To eCourier
 * Plugin URI:      https://simongomes.dev
 * Description:     Send To eCourier gives you ability to send your parcel request to eCourier directly from your WooCommerce dashboard, it enables booking automation from your WordPress website.
 * Author:          Simon Gomes
 * Author URI:      https://simongomes.dev
 * Text Domain:     send-to-ecourier
 * Domain Path:     /languages
 * Version:         1.0.1
 * License:         GPLv3
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package         SendToEcourier
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

if ( ! class_exists( 'Send_To_Ecourier' ) ) {
	/**
	 * Register the main plugin class.
	 *
	 * Class Send_To_Ecourier
	 */
	final class Send_To_Ecourier {

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		const VERSION = '1.0.1';

		/**
		 * Send_To_Ecourier constructor.
		 *
		 * @return void
		 */
		public function __construct() {
			$this->define_constants();

			register_activation_hook( __FILE__, array( $this, 'activate' ) );
		}

		/**
		 * Initialize a singleton instance.
		 *
		 * @return \Send_To_Ecourier
		 */
		public static function init() {
			$instance = false;

			if ( ! $instance ) {
				$instance = new self();
			}

			return $instance;
		}

		/**
		 * Defines all necessary constants for the plugin.
		 *
		 * @return void
		 */
		public function define_constants() {
			define( 'STE_VERSION', self::VERSION );
			define( 'STE_FILE', __FILE__ );
			define( 'STE_PATH', __DIR__ );
			define( 'STE_URL', plugins_url( '', STE_FILE ) );
			define( 'STE_ASSETS', STE_URL . '/assets' );
			define( 'STE_API_BASE_URL_STAGING', 'https://staging.ecourier.com.bd/api' );
			define( 'STE_API_BASE_URL_LIVE', 'https://backoffice.ecourier.com.bd/api' );
		}

		/**
		 * Necessary setup on plugin activation.
		 *
		 * @return void
		 */
		public function activate() {
			$installer = new SendToEcourier\Installer();
			$installer->run();
		}

	}
}

/**
 * Initialize the main plugin.
 *
 * @return \Send_To_Ecourier|bool
 */
function send_to_ecourier() {
	return Send_To_Ecourier::init();
}

// Kick-off the plugin.
send_to_ecourier();
