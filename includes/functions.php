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
