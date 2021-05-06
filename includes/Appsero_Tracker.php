<?php // phpcs:ignore
/**
 * This file is responsible for Appsero tracker to track plugin analytics data.
 *
 * @package ShipToEcourier
 */
namespace ShipToEcourier;

use Appsero\Client;

if ( ! class_exists( 'Appsero_Tracker' ) ) {
	/**
	 * This will register and initialize Appsero to analytics for the plugin.
	 *
	 * Class Appsero
	 *
	 * @package SimonGomes\EPT
	 */
	class Appsero_Tracker {
		/**
		 * Initialize the Appsero tracker for the plugin.
		 *
		 * @return void
		 */
		public static function init_tracker() {
			if ( ! class_exists( 'Appsero\Client' ) ) {
				require_once STE_PATH . '/vendor/appsero/client/src/Client.php';
			}

			$client = new Client( '64ebd188-5483-4b74-a8ec-7f73a9b238c4', 'Ship To Ecourier', STE_FILE );

			// Active insights.
			$client->insights()->init();
		}

	}
}
