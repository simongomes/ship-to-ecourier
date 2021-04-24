<?php // php:ignore
/**
 * This file is responsible for registering and organizing all assets ( JavaScripts and CSS ) necessary for the Ship To Ecourier plugin.
 *
 * @package ShipToEcourier
 */
namespace ShipToEcourier;

if ( ! class_exists( 'Assets' ) ) {

	/**
	 * Class Assets
	 *
	 * Registers necessary assets like js, css for the Ship To Ecourier plugin
	 */
	class Assets {

		/**
		 * Get an array of necessary css styles for the plugin.
		 *
		 * @return array[]
		 */
		public function get_styles() {
			return array(
				'ste-styles' => array(
					'src'     => STE_ASSETS_URL . '/css/frontend.css',
					'deps'    => array(),
					'version' => SET_VERSION,
				),
			);
		}

		/**
		 * Get an array of necessary scripts for the plugin.
		 *
		 * @return array[]
		 */
		public function get_scripts() {
			return array(
				'ste-lodash'          => array(
					'src'       => STE_ASSETS_URL . '/js/lodash.js',
					'deps'      => array(),
					'version'   => SET_VERSION,
					'in_footer' => true,
				),
				'ste-moment'          => array(
					'src'       => STE_ASSETS_URL . '/js/moment.js',
					'deps'      => array(),
					'version'   => SET_VERSION,
					'in_footer' => true,
				),
				'ste-frontend-script' => array(
					'src'       => STE_ASSETS_URL . '/js/frontend.js',
					'deps'      => array( 'jquery', 'ste-lodash', 'ste-moment' ),
					'version'   => SET_VERSION,
					'in_footer' => true,
				),
			);
		}

		/**
		 * Register all necessary CSS and JavaScript for the plugin.
		 *
		 * @return void
		 */
		public function enqueue_assets() {
			$styles = $this->get_styles();

			foreach ( $styles as $handle => $style ) {
				wp_register_style( $handle, $style['src'], $style['deps'], $style['version'] );
			}

			$scripts = $this->get_scripts();

			foreach ( $scripts as $handle => $script ) {
				wp_register_script( $handle, $script['src'], $script['deps'], $script['version'], $script['in_footer'] );
			}

			wp_localize_script(
				'ept-script',
				'STE',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'error'   => __( 'Something went wrong!', 'ship-to-ecourier' ),
				)
			);

		}
	}
}