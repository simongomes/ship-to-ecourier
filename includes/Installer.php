<?php // phpcs:ignore

namespace ShipToEcourier;

if ( ! class_exists( 'Installer' ) ) {
	/**
	 * Class Installer
	 *
	 * Sets up necessary configuration for the plugin. Takes care of database related configurations as well.
	 *
	 * @package ShipToEcourier
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
			$this->create_shipped_orders_table();
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

		/**
		 * Create the shipped_order table to store the information of
		 * successfully shipped orders to eCourier.
		 *
		 * @return void
		 */
		public function create_shipped_orders_table() {
			global $wpdb;

			$charset_collate = $wpdb->get_charset_collate();
			$table_name      = $wpdb->prefix . STE_TABLE_PREFIX . 'shipped_orders';

			$schema = "CREATE TABLE `{$table_name}` ( 
    				  	`ID` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    					`order_id` BIGINT(20) UNSIGNED NOT NULL,
    					`tracking_id` VARCHAR(191) NOT NULL,
    					`created_by` BIGINT(20) UNSIGNED NOT NULL,
    					`created_at` TIMESTAMP NOT NULL,
    					PRIMARY KEY (`ID`)
                     ) $charset_collate;";

			if ( ! function_exists( 'dbDelta' ) ) {
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			}

			dbDelta( $schema );
		}

	}
}
