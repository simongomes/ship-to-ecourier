<?php
/**
 * Handler for ecourier.
 *
 * all apis will handle here.
 */

namespace ShipToEcourier;

/**
 * Class Ecourier_Handler
 *
 * @package ShipToEcourier
 */
class Ecourier_Handler {

	const CITY_LIST_CACHE_KEY = 'ste_city_list';
	const AREA_LIST_CACHE_KEY = '_ste_area_list';

	/**
	 * Base URL.
	 */
	public $base_url;

	public function __construct() {
		$this->set_base_url();
	}

	/**
	 * Set base url based on settings.
	 *
	 * @return void
	 */
	public function set_base_url() {
		// Get eCourier API configs.
		$settings = ste_get_settings();

		// Get eCourier API URL (Staging/Live).
		$this->base_url = 'live' === $settings['api_environment'] ? STE_API_BASE_URL_LIVE : STE_API_BASE_URL_STAGING;
	}

	/**
	 * Get header for ecourier api.
	 *
	 * @return array
	 */
	public function get_headers() {
		// Get eCourier API configs.
		$settings = ste_get_settings();

		return [
			'USER-ID'      => $settings['user_id'],
			'API-KEY'      => $settings['api_key'],
			'API-SECRET'   => $settings['api_secret'],
			'Content-Type' => 'application/json',
		];
	}

	/**
	 * Get City list.
	 *
	 * @return \WP_Error|array
	 */
	public function get_city_list() {
		$cities = get_transient( self::CITY_LIST_CACHE_KEY );

		if ( $cities ) {
			return $cities;
		}

		$url = $this->base_url . '/city-list';

		$response = wp_remote_post(
			$url,
			array(
				'method'  => 'POST',
				'headers' => $this->get_headers(),
			)
		);

		if ( is_wp_error( $response ) ) {
			return new \WP_Error( 'ste_get_city_error', __( 'Error in getting city list', 'ship-to-ecourier' ), [ 'status' => 500 ] );
		}

		$body = wp_remote_retrieve_body( $response );

		$cities = json_decode( $body, true );

		//keeping data in cache. Expire in 48 hours.
		set_transient( self::CITY_LIST_CACHE_KEY, $cities, 172800 );

		return $cities;
	}

	/**
	 * Get area by district.
	 *
	 * @param string $district District name.
	 *
	 * @return array|\WP_Error
	 */
	public function get_area_by_district( $district ) {
		$cache_key = $district . self::AREA_LIST_CACHE_KEY;

		$areas_data = get_transient( $cache_key );

		if ( $areas_data ) {
			return $areas_data;
		}

		$url = $this->base_url . '/area-by-district';

		$response = wp_remote_post(
			$url,
			[
				'headers' => $this->get_headers(),
				'body'    => wp_json_encode( [ 'district' => str_replace( "\'", "'", $district ) ] ),
			]
		);

		if ( is_wp_error( $response ) ) {
			return new \WP_Error( 'ste_get_area_error', __( 'Error in getting Area list', 'ship-to-ecourier' ), [ 'status' => 500, 'data' => $response->get_error_data() ] );
		}

		$body = wp_remote_retrieve_body( $response );

		$areas = json_decode( $body, true );

		if ( isset( $areas['success'] ) && false === $areas['success'] ) {
			return new \WP_Error(
				'ste_get_area_error',
				__( 'Error in getting Area list', 'ship-to-ecourier' ),
				[
					'status' => 500,
					'data' => $areas['errors'] ? $areas['errors'] : $areas['data']
				]
			);
		}

		$areas_data = $areas['data'];

		//keeping data in cache. Expire in 48 hours.
		set_transient( $cache_key, $areas_data, 172800 );

		return $areas_data;
	}
}
