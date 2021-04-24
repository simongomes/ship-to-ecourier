<?php // phpcs:ignore
/**
 * This file handles all the necessary Ajax requests for the `Ship To eCourier` plugin.
 *
 * @package ShipToEcourier
 */
namespace ShipToEcourier;

if ( ! class_exists( 'Ajax' ) ) {
	/**
	 * Class Ajax
	 *
	 * Handles all the necessary Ajax requests, API for submissions and returns the necessary data.
	 *
	 * @package ShipToEcourier
	 */
	class Ajax {

		/**
		 * Ajax constructor, registers the actions.
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'wp_ajax_ste_parcel_tracking_form', array( $this, 'ste_handle_parcel_tracker_form_submission' ) );
			add_action( 'wp_ajax_nopriv_ste_parcel_tracking_form', array( $this, 'ste_handle_parcel_tracker_form_submission' ) );
		}

		/**
		 * Handle the tracking form submission. Get parcel status from eCourier and return back to front end.
		 *
		 * @return void
		 */
		public function ste_handle_parcel_tracker_form_submission() {

			// Block if valid nonce field is not available and valid.
			check_ajax_referer( 'ste-frontend-nonce', 'nonce' );

			$settings = ste_get_settings();

			if ( isset( $_POST['tracking_code'] ) ) {
				$tracking_code = sanitize_text_field( wp_unslash( $_POST['tracking_code'] ) );

				$ecourier_api_url = 'live' === $settings['api_environment'] ? STE_API_BASE_URL_LIVE . '/track' : STE_API_BASE_URL_STAGING . '/track';

				$response = wp_remote_post(
					$ecourier_api_url,
					array(
						'method'  => 'POST',
						'headers' => array(
							'USER-ID'    => $settings['user_id'],
							'API-KEY'    => $settings['api_key'],
							'API-SECRET' => $settings['api_secret'],
						),
						'body'    => array(
							'ecr' => $tracking_code,
						),
					)
				);

				wp_send_json_success(
					array(
						'message' => $response['body'],
					)
				);
			}

			wp_send_json_error(
				array(
					'message' => __( 'Please provide a valid tracking code.', 'ecourier-parcel-tracker' ),
				)
			);

		}
	}
}
