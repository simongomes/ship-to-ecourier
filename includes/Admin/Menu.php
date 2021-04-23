<?php //phpcs:ignore
/**
 * Menu class registers the admin navigation menu.
 *
 * @package SendToEcourier
 */
namespace SendToEcourier\Admin;

if ( ! class_exists( 'Menu' ) ) {
	/**
	 * Class Menu
	 *
	 * Menu class handles the admin menu bar.
	 *
	 * @package SendToEcourier\Admin
	 */
	class Menu {
		/**
		 * Holds the Settings class instance.
		 *
		 * @var $settings
		 */
		public $settings;

		/**
		 * Menu constructor.
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		}

		/**
		 * Register the admin menu for `Send To eCourier` plugin.
		 *
		 * @return void
		 */
		public function add_admin_menu() {
			$parent_slug = 'send-to-ecourier';
			$capability  = 'manage_options';
			add_menu_page( __( 'eCourier Settings', 'send-to-ecourier' ), __( 'eCourier Settings', 'send-to-ecourier' ), $capability, $parent_slug, null, STE_ASSETS_URL . '/images/menu_icon.png' );
		}
	}
}
