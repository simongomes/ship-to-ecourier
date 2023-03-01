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
		 * Contains eCourier API base url.
		 *
		 * @var string
		 */
		public $ecourier_base_url = '';

		/**
		 * Contains eCourier API credentials.
		 *
		 * @var array
		 */
		public $settings = array();

		/**
		 * Ajax constructor, registers the actions.
		 *
		 * @return void
		 */
		public function __construct() {

			// Get eCourier API credentials.
			$this->settings = ste_get_settings();

			// Set eCourier base url.
			$this->ecourier_base_url = 'live' === $this->settings['api_environment'] ? STE_API_BASE_URL_LIVE : STE_API_BASE_URL_STAGING;

			add_action( 'wp_ajax_ste_get_area_by_district', array( $this, 'handle_ste_get_area_by_district' ) );
			add_action( 'wp_ajax_ste_parcel_tracking_form', array( $this, 'handle_parcel_tracker_form_submission' ) );
			add_action( 'wp_ajax_nopriv_ste_parcel_tracking_form', array( $this, 'handle_parcel_tracker_form_submission' ) );
			add_action( 'wp_ajax_ste_booking_metabox_form', array( $this, 'handle_booking_metabox_form_submission' ) );
			add_action( 'wp_ajax_ste_label_print', array( $this, 'handle_label_print' ) );
			add_action( 'wp_ajax_ste_cancel_parcel_request', array( $this, 'handle_parcel_cancel_request' ) );

		}

		public function handle_ste_get_area_by_district() {
			if ( ! isset( $_POST['_nonce'] ) || empty( $_POST['district'] ) ) {
				wp_send_json_error( __( 'Something went wrong here!', 'ship-to-ecourier' ) );
				wp_die();
			}

			// Block if _nonce field is not available and valid.
			check_ajax_referer( 'ste-admin-nonce', '_nonce' );

			$areas = ship_to_ecourier()->ecourier->get_area_by_district( sanitize_text_field( $_POST['district'] ) );

			if ( is_wp_error( $areas ) ) {
				wp_send_json_error( $areas->get_error_message() );
				wp_die();
			}

			wp_send_json_success( $areas );
		}

		/**
		 * Handle the tracking form submission. Get parcel status from eCourier and return back to front end.
		 *
		 * @return void
		 */
		public function handle_parcel_tracker_form_submission() {

			// Block if valid nonce field is not available and valid.
			check_ajax_referer( 'ste-frontend-nonce', 'nonce' );

			if ( isset( $_POST['tracking_code'] ) ) {

				$tracking_code = sanitize_text_field( wp_unslash( $_POST['tracking_code'] ) );

				$ecourier_api_url = $this->ecourier_base_url . '/track';

				// Make request to eCourier API.
				$response = $this->make_request( $ecourier_api_url, array( 'ecr' => $tracking_code ) );

				if ( is_wp_error( $response ) ) {
					wp_send_json_error(
						array(
							'message' => $response->get_error_data(),
						)
					);
				}

				// Send response to front-end.
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
		public function handle_booking_metabox_form_submission() {

			if ( ! isset( $_POST['submit_ste_ecourier_parcel'] ) || ! isset( $_POST['_nonce'] ) ) {
				die( 'Request is not valid!' );
			}

			// Block if _nonce field is not available and valid.
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
				! isset( $_POST['product_price'] ) || '' === $_POST['product_price'] ||
				! isset( $_POST['number_of_item'] ) || '' === $_POST['number_of_item'] ||
				! isset( $_POST['comments'] ) || '' === $_POST['comments']
			) {
				wp_send_json_error(
					array(
						'message' => __( 'All fields are required!', 'ship-to-ecourier' ),
					)
				);
				return;
			}

			// Generate parcel booking data to send to eCourier.
			$parcel_data = array(
				'recipient_name'    => sanitize_text_field( wp_unslash( $_POST['recipient_name'] ) ),
				'recipient_mobile'  => sanitize_text_field( wp_unslash( $_POST['recipient_mobile'] ) ),
				'recipient_city'    => ucwords( sanitize_text_field( wp_unslash( $_POST['recipient_city'] ) ) ),
				'recipient_area'    => ucwords( sanitize_text_field( wp_unslash( $_POST['recipient_area'] ) ) ),
				'recipient_thana'   => ucwords( sanitize_text_field( wp_unslash( $_POST['recipient_thana'] ) ) ),
				'recipient_zip'     => sanitize_text_field( wp_unslash( $_POST['recipient_zip'] ) ),
				'recipient_address' => sanitize_text_field( wp_unslash( $_POST['recipient_address'] ) ),
				'payment_method'    => sanitize_text_field( wp_unslash( $_POST['payment_method'] ) ),
				'package_code'      => sanitize_text_field( wp_unslash( $_POST['package_code'] ) ),
				'product_id'        => sanitize_text_field( wp_unslash( $_POST['product_id'] ) ),
				'product_price'     => sanitize_text_field( wp_unslash( $_POST['product_price'] ) ),
				'number_of_item'    => sanitize_text_field( wp_unslash( $_POST['number_of_item'] ) ),
				'comments'          => sanitize_text_field( wp_unslash( $_POST['comments'] ) ),
			);

			$ecourier_api_url = $this->ecourier_base_url . '/order-place';

			// Send parcel booking request to eCourier.
			$response = $this->make_request( $ecourier_api_url, $parcel_data );

			if ( is_wp_error( $response ) ) {
				wp_send_json_error(
					array(
						'message' => $response->get_error_data(),
					)
				);
				return;
			}

			$result = json_decode( $response['body'], true );

			if ( $result['success'] ) {
				// Insert order shipped record to ste_shipped_orders table.
				$insert = ste_insert_shipped_order(
					array(
						'order_id'    => $parcel_data['product_id'],
						'tracking_id' => $result['ID'],
					)
				);

				// If WP_Error send error message back to admin panel.
				if ( is_wp_error( $insert ) ) {
					wp_send_json_error(
						array(
							'message' => $insert->get_error_message(),
						)
					);
					return;
				}

				$user = get_user_by( 'id', get_current_user_id() );

				$order_shipped_note = '';

				if ( $user ) {
					$order_shipped_note = sprintf(
						__( 'Order shipped to Ecourier by %s', 'ship-to-ecourier' ),
						$user->display_name
					);
				}

				/**
				 * Get the order to update the order status.
				 *
				 * using original order number here because sometimes the order
				 * number might be modified through `woocommerce_order_number` filter
				 * by third party plugin.
				 */
				$order = new \WC_Order( sanitize_text_field( wp_unslash( $_POST['original_order_number'] ) ) );
				! empty( $order_shipped_note ) ? $order->add_order_note( $order_shipped_note ) : null;
				$order->update_status( 'shipped' );
			}

			// Send response back to admin panel.
			wp_send_json_success(
				array(
					'message' => $response['body'],
				)
			);
		}


		/**
		 * Handle label print request for parcel bookings.
		 *
		 * @retun void
		 */
		public function handle_label_print() {
			// Check if necessary fields are available.
			if ( ! isset( $_POST['tracking'] ) || ! isset( $_POST['_nonce'] ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Request is not valid!', 'ship-to-ecourier' ),
					)
				);
			}

			// Block if _nonce field is not available and valid.
			check_ajax_referer( 'ste-admin-nonce', '_nonce' );

			// Generate label print data to send to eCourier.
			$label_data = array(
				'tracking' => sanitize_text_field( wp_unslash( $_POST['tracking'] ) ),
			);

			$ecourier_api_url = $this->ecourier_base_url . '/label-print';

			// Send parcel label print request to eCourier.
			$response = $this->make_request( $ecourier_api_url, $label_data );

			if ( is_wp_error( $response ) ) {
				wp_send_json_error(
					array(
						'message' => $response->get_error_data(),
					)
				);
			}

			$result = json_decode( $response['body'], true );

			if ( ! $result['success'] ) {
				wp_send_json_error(
					array(
						'message' => $result['query_data'],
					)
				);
			}

			wp_send_json_success(
				array(
					'message' => $result['query_data'],
				)
			);
		}

		/**
		 * Handle parcel cancel request.
		 *
		 * @retun void
		 */
		public function handle_parcel_cancel_request() {
			// Check if necessary fields are available.
			if ( ! isset( $_POST['tracking'] ) || ! isset( $_POST['_nonce'] ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Request is not valid!', 'ship-to-ecourier' ),
					)
				);
			}

			// Block if _nonce field is not available and valid.
			check_ajax_referer( 'ste-admin-nonce', '_nonce' );

			$user = get_user_by( 'id', get_current_user_id() );

			// Generate label print data to send to eCourier.
			$label_data = array(
				'tracking' => sanitize_text_field( wp_unslash( $_POST['tracking'] ) ),
				'comment'  => __( 'Please cancel the parcel. Requested by ' . $user->display_name , 'ship-to-ecourier' ),
			);

			$ecourier_api_url = $this->ecourier_base_url . '/cancel-order';

			// Send parcel cancel request to eCourier.
			$response = $this->make_request( $ecourier_api_url, $label_data );

			if ( is_wp_error( $response ) ) {
				wp_send_json_error(
					array(
						'message' => $response->get_error_data(),
					)
				);
			}

			$result = json_decode( $response['body'], true );

			if ( ! 'true' === $result['success'] ) {
				wp_send_json_error(
					array(
						'message' => $result['message'],
					)
				);
			} else {
				$deleted = ste_remove_shipped_order( sanitize_text_field( wp_unslash( $_POST['tracking'] ) ) );

				// If WP_Error send error message back to admin panel.
				if ( is_wp_error( $deleted ) ) {
					wp_send_json_error(
						array(
							'message' => $deleted->get_error_message(),
						)
					);
				}
			}

			$order = wc_get_order( wp_unslash( $_POST['original_order_number'] ) );

			if ( $order instanceof \WC_Order) {
				$order_cancel_note = '';

				if ( $user ) {
					$order_cancel_note = sprintf(
						__( 'Shipping cancelled at Ecourier by %s', 'ship-to-ecourier' ),
						$user->display_name
					);
				}

				! empty( $order_cancel_note ) ? $order->add_order_note( $order_cancel_note ) : null;

				$order->update_status( 'processing' );
			}

			wp_send_json_success(
				array(
					'message' => $result['message'],
				)
			);
		}

		/**
		 * Make request to eCourier API.
		 *
		 * @param string $url API end-point.
		 * @param array  $params API request parameters.
		 *
		 * @return array|\WP_Error
		 */
		public function make_request( $url, $params = array() ) {

			return wp_remote_post(
				$url,
				array(
					'method'  => 'POST',
					'headers' => array(
						'USER-ID'      => $this->settings['user_id'],
						'API-KEY'      => $this->settings['api_key'],
						'API-SECRET'   => $this->settings['api_secret'],
						'Content-Type' => 'application/json',
					),
					'body'    => wp_json_encode( $params ),
				)
			);

		}
	}
}
