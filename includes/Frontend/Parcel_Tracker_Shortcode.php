<?php // phpcs:ignore
/**
 * This file registers and handles the shortcode for eCourier parcel tracker.
 *
 * @package ShipToEcourier\Frontend
 */
namespace ShipToEcourier\Frontend;

if ( ! class_exists( 'Parcel_Tracker_Shortcode' ) ) {
	/**
	 * Class Parcel_Tracker_Shortcode
	 *
	 * The class handles the frontend shortcode for the parcel tracker.
	 *
	 * @package ShipToEcourier\Frontend
	 */
	class Parcel_Tracker_Shortcode {

		/**
		 * Parcel_Tracker_Shortcode constructor.
		 *
		 * @return void
		 */
		public function __construct() {
			add_shortcode( 'ecourier-parcel-tracker', array( $this, 'render_shortcode' ) );
		}

		/**
		 * Render the parcel tracker frontend using a shortcode.
		 *
		 * @return string
		 */
		public function render_shortcode() {

			// enqueue all frontend assets.
			wp_enqueue_style( 'ste-frontend-styles' );
			wp_enqueue_script( 'ste-frontend-script' );

			ob_start();

			include __DIR__ . '/views/form-parcel-tracker.php';

			return ob_get_clean();
		}
	}
}
