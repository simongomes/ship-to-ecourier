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
			add_action( 'wp_ajax_ste_booking_metabox_form', array( $this, 'ste_handle_booking_metabox_form_submission' ) );

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

		/**
		 * Handle the booking metabox form submission, and generate a parcel booking to eCourier platform.
		 *
		 * @return void
		 */
		public function ste_handle_booking_metabox_form_submission() {
			if ( ! isset( $_POST['submit_ste_ecourier_parcel'] ) || ! isset( $_POST['_nonce'] ) ) {
				die( 'Request is not valid!' );
			}
			// Block if valid nonce field is not available and valid.
			check_ajax_referer( 'ste-admin-nonce', '_nonce' );

			// Post data validation.
			if (
				! isset( $_POST['recipient_name'] ) || '' === $_POST['recipient_name'] ||
				! isset( $_POST['recipient_mobile'] ) || '' === $_POST['recipient_mobile'] ||
				! isset( $_POST['recipient_city'] ) || '' === $_POST['recipient_city'] ||
				! isset( $_POST['recipient_area'] ) || '' === $_POST['recipient_area'] ||
				! isset( $_POST['recipient_thana'] ) || '' === $_POST['recipient_thana'] ||
				! isset( $_POST['recipient_zip'] ) || '' === $_POST['recipient_zip'] ||
				! isset( $_POST['recipient_address'] ) || '' === $_POST['recipient_address'] ||
				! isset( $_POST['payment_method'] ) || '' === $_POST['payment_method'] ||
				! isset( $_POST['package_code'] ) || '' === $_POST['package_code'] ||
				! isset( $_POST['package_code'] ) || '' === $_POST['package_code'] ||
				! isset( $_POST['product_id'] ) || '' === $_POST['product_id'] ||
				! isset( $_POST['number_of_item'] ) || '' === $_POST['number_of_item'] ||
				! isset( $_POST['comments'] ) || '' === $_POST['comments']
			) {
				wp_send_json_error(
					array(
						'message' => __( 'All fields are required!', 'ship-to-ecourier' ),
					)
				);
			}

			// Generate parcel booking data to send to eCourier.
			$parcel_data = array(
				'recipient_name' => isset( $_POST['recipient_name'] ) ? sanitize_text_field( wp_unslash( $_POST['recipient_name'] ) ) : '',
			);

			wp_send_json_success(
				$_POST
			);
		}
	}
}
