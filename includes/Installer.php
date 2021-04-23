<?php // phpcs:ignore

namespace SendToEcourier;

if ( ! class_exists( 'Installer' ) ) {
	/**
	 * Class Installer
	 *
	 * Sets up necessary configuration for the plugin. Takes care of database related configurations as well.
	 *
	 * @package SendToEcourier
	 */
	class Installer {

		/**
		 * Run the Installer and initiate the class methods.
		 *
		 * @return void
		 */
		public function run() {
			$this->manage_version();
			$this->create_api_settings_options();
		}

		/**
		 * Manages plugin version.
		 *
		 * @return void
		 */
		private function manage_version() {
			$installed = get_option( 'ste_installed' );

			if ( ! $installed ) {
				update_option( 'ste_installed', time() );
			}

			update_option( 'ste_version', STE_VERSION );
		}

		/**
		 * Create the options for eCourier API settings
		 *
		 * @return void
		 */
		public function create_api_settings_options() {

			if ( ! get_option( 'ste_settings' ) ) {
				$settings = array(
					'user_id'         => '',
					'api_key'         => '',
					'api_secret'      => '',
					'api_environment' => 'staging',
				);

				add_option( 'ste_settings', $settings );
			}
		}

	}
}
