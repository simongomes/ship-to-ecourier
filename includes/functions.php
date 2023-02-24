<?php
/**
 * This file container functions to handle database transactions.
 *
 * @package ShipToEcourier
 */

/**
 * Handles the database insertion for api credentials.
 *
 * @param array $args array of api credentials.
 *
 * @return \WP_Error|bool
 */
function ste_insert_settings( $args = array() ) {

	if ( empty( $args['user_id'] ) || empty( $args['api_key'] ) || empty( $args['api_secret'] ) || empty( $args['api_environment'] ) ) {
		return new \WP_Error( 'required-field-missing', __( 'All fields are required.', 'ecourier-parcel-tracker' ) );
	}

	$inserted = update_option( 'ste_settings', $args );

	if ( ! $inserted ) {
		return new \WP_Error( 'failed-to-insert', __( 'Failed to insert settings data', 'ecourier-parcel-tracker' ) );
	}

	return true;
}

/**
 * Fetch eCourier API credentials from database.
 *
 * @return array
 */
function ste_get_settings() {
	// Get eCourier API configurations from options table.
	$settings = get_option( 'ste_settings' );

	return $settings;
}

/**
 * Get eCourier packages for the connected account.
 *
 * @return array|\WP_Error
 */
function ste_get_ecourier_packages() {

	$cache_key = 'ste_packages_list';

	$packages = get_transient( $cache_key );

	if ( $packages ) {
		return $packages;
	}

	// Get eCourier API configs.
	$settings = ste_get_settings();

	// Get eCourier API URL (Staging/Live).
	$ecourier_api_url = 'live' === $settings['api_environment'] ? STE_API_BASE_URL_LIVE . '/packages' : STE_API_BASE_URL_STAGING . '/packages';

	// Send request to eCourier to fetch the active packages.
	$response = wp_remote_post(
		$ecourier_api_url,
		array(
			'method'  => 'POST',
			'headers' => array(
				'USER-ID'    => $settings['user_id'],
				'API-KEY'    => $settings['api_key'],
				'API-SECRET' => $settings['api_secret'],
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		return new WP_Error( 'ste_package_fetch_error', 'Error in getting packages', [ 'status' => 500 ] );
	}

	$packages = json_decode( $response['body'] );

	//keeping data in cache. Expire in 48 hours.
	set_transient( $cache_key, $packages, 172800 );

	return $packages;
}

/**
 * Insert successfully shipped order information to database.
 *
 * @param array $args shipped order information.
 *
 * @return bool|\WP_Error
 */
function ste_insert_shipped_order( $args = array() ) {
	global $wpdb;

	$table_name = $wpdb->prefix . STE_TABLE_PREFIX . 'shipped_orders';

	$defaults = array(
		'order_id'    => '',
		'tracking_id' => '',
		'created_by'  => get_current_user_id(),
		'created_at'  => current_time( 'mysql' ),
	);

	$data = wp_parse_args( $args, $defaults );

	$inserted = $wpdb->insert(
		$table_name,
		$data,
		array( '%d', '%s', '%d', '%s' )
	); // db call ok; no-cache ok.

	if ( ! $inserted ) {
		return new \WP_Error( 'failed-to-insert-shipped-order', __( 'Failed to insert shipped order information!', 'ship-to-ecourier' ) );
	}

	return true;
}

/**
 * Remove shipped order information from database.
 *
 * @param string $tracking_id shipped order tracking ID.
 *
 * @return bool|\WP_Error
 */
function ste_remove_shipped_order( $tracking_id ) {
	global $wpdb;

	$table_name = $wpdb->prefix . STE_TABLE_PREFIX . 'shipped_orders';

	$deleted = $wpdb->delete(
		$table_name,
		array( 'tracking_id' => $tracking_id )
	); // db call ok; no-cache ok.

	if ( ! $deleted ) {
		return new \WP_Error( 'failed-to-removed-shipped-order', __( 'Failed to removed shipped order information!', 'ship-to-ecourier' ) );
	}

	return true;
}

/**
 * Check if the order is already shipped and return the status.
 *
 * @param int $order_id WC order number.
 *
 * @return array|object|bool
 */
function ste_get_order_shipping_info( $order_id ) {
	global $wpdb;

	$table_name = $wpdb->prefix . STE_TABLE_PREFIX . 'shipped_orders';

	$order_shipment_status = $wpdb->get_results( "SELECT * FROM `{$table_name}` WHERE `order_id`={$order_id}"); // phpcs:ignore

	if ( empty( $order_shipment_status ) ) {
		return false;
	}
	return $order_shipment_status[0];
}
