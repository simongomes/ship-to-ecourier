<?php // phpcs:ignore
/**
 * This file handles, loads and organizes the admin-end related classes dnd functionalities.
 *
 * @package ShipToEcourier
 */
namespace ShipToEcourier;

if ( ! class_exists( 'Admin' ) ) {
	/**
	 * Class Admin
	 *
	 * Initializes all admin-end classes and functionalities.
	 *
	 * @package SendToEcourier
	 */
	class Admin {

		/**
		 * Admin constructor.
		 *
		 * @return void
		 */
		public function __construct() {
			$settings = new Admin\Settings();

			$this->dispatch_actions( $settings );

			new Admin\Menu( $settings );
		}

		/**
		 * Dispatch necessary actions for the plugin.
		 *
		 * @param \ShipToEcourier\Admin\Settings $settings Settings class instance.
		 *
		 * @return void
		 */
		public function dispatch_actions( Admin\Settings $settings ) {
			add_action( 'admin_init', array( $settings, 'ste_get_api_configurations' ) );
			add_action( 'admin_init', array( $settings, 'ste_settings_form_handler' ) );
		}

	}
}
