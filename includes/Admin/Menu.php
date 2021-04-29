<?php //phpcs:ignore
/**
 * Menu class registers the admin navigation menu.
 *
 * @package ShipToEcourier\Admin
 */
namespace ShipToEcourier\Admin;

if ( ! class_exists( 'Menu' ) ) {
	/**
	 * Class Menu
	 *
	 * Menu class handles the admin menu bar.
	 *
	 * @package ShipToEcourier\Admin
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
		 * @param \ShipToEcourier\Admin\Settings $settings Settings class instance.
		 *
		 * @return void
		 */
		public function __construct( Settings $settings ) {
			// Set the active Settings class instance.
			$this->settings = $settings;

			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( STE_FILE ), array( $this, 'add_settings_menu' ) );
		}

		/**
		 * Register the admin menu for `Send To eCourier` plugin.
		 *
		 * @return void
		 */
		public function add_admin_menu() {
			$parent_slug = 'ship-to-ecourier';
			$capability  = 'manage_options';
			add_menu_page( __( 'eCourier Settings', 'ship-to-ecourier' ), __( 'eCourier Settings', 'send-to-ecourier' ), $capability, $parent_slug, array( $this->settings, 'load_settings_page' ), STE_ASSETS_URL . '/images/menu_icon.png' );
		}

		/**
		 * Add settings menu link to plugin page.
		 *
		 * @param array $links plugin links.
		 *
		 * @return array
		 */
		public function add_settings_menu( array $links ) {
			$settings_link = '<a href="' . admin_url( 'admin.php?page=ship-to-ecourier' ) . '">' . __( 'Settings', 'ship-to-ecourier' ) . '</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}
	}
}
