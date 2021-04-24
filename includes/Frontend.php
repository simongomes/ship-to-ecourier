<?php // phpcs:ignore
/**
 * This file handles, loads and organizes the frontend related classes and functionalities.
 *
 * @package ShipToEcourier
 */
namespace ShipToEcourier;

if ( ! class_exists( 'Frontend' ) ) {
	/**
	 * Class Frontend
	 *
	 * Initializes all frontend related classes.
	 *
	 * @package ShipToEcourier
	 */
	class Frontend {

		/**
		 * Frontend constructor.
		 *
		 * @return void
		 */
		public function __construct() {
			new Frontend\Parcel_Tracker_Shortcode();
		}

	}
}
