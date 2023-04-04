<?php // phpcs:ignore
/**
 * Plugin Name:     Ship To eCourier
 * Plugin URI:      https://github.com/simongomes/ship-to-ecourier
 * Description:     Ship To eCourier gives you ability to send your parcel request to eCourier directly from your WooCommerce dashboard, it enables booking automation from your WordPress website.
 * Author:          Simon Gomes
 * Author URI:      https://simongomes.dev
 * Text Domain:     ship-to-ecourier
 * Domain Path:     /languages
 * Version:         1.1.1
 * License:         GPLv3
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package         ShipToEcourier
 */

/**
 * Copyright (c) 2021 Simon Gomes (email: busy.s.simon@gmail.com). All rights reserved.
 *
 * Released under the GPL license
 * https://www.gnu.org/licenses/gpl-3.0.html
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

use ShipToEcourier\Appsero_Tracker;

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

if ( ! class_exists( 'Ship_To_Ecourier' ) ) {
	/**
	 * Register the main plugin class.
	 *
	 * Class Ship_To_Ecourier
	 */
	final class Ship_To_Ecourier {

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		const VERSION = '1.1.0';

		/**
		 * Holds various class instances.
		 *
		 * @var array
		 */
		private $container = [];

		/**
		 * Ship_To_Ecourier constructor.
		 *
		 * @return void
		 */
		public function __construct() {
			$this->define_constants();

			register_activation_hook( __FILE__, array( $this, 'activate' ) );

			add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );

			add_action( 'init', [ $this, 'init_classes' ] );
		}

		/**
		 * Initialize a singleton instance.
		 *
		 * @return \Ship_To_Ecourier
		 */
		public static function init() {
			static $instance = false;

			if ( ! $instance ) {
				$instance = new self();
			}

			return $instance;
		}

		/**
		 * Magic getter to bypass referencing plugin.
		 *
		 * @param mixed $prop Properties to find.
		 *
		 * @return mixed
		 */
		public function __get( $prop ) {
			if ( array_key_exists( $prop, $this->container ) ) {
				return $this->container[ $prop ];
			}

			return $this->{$prop};
		}

		/**
		 * Magic isset to bypass referencing plugin.
		 *
		 * @param mixed $prop Properties to find.
		 *
		 * @return mixed
		 */
		public function __isset( $prop ) {
			return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
		}

		/**
		 * Defines all necessary constants for the plugin.
		 *
		 * @return void
		 */
		public function define_constants() {
			define( 'STE_VERSION', self::VERSION );
			define( 'STE_FILE', __FILE__ );
			define( 'STE_PATH', __DIR__ );
			define( 'STE_URL', plugins_url( '', STE_FILE ) );
			define( 'STE_ASSETS_URL', STE_URL . '/assets' );
			define( 'STE_TABLE_PREFIX', 'ste_' );
			define( 'STE_API_BASE_URL_STAGING', 'https://staging.ecourier.com.bd/api' );
			define( 'STE_API_BASE_URL_LIVE', 'https://backoffice.ecourier.com.bd/api' );
		}

		/**
		 * Load plugin classes and initialize assets.
		 *
		 * @return void
		 */
		public function init_plugin() {
			// Initialize the Appsero tracker for plugin analytics.
			Appsero_Tracker::init_tracker();

			// Call Assets class to load necessary assets for plugin ( JavaScript and CSS ).
			new ShipToEcourier\Assets();

			// Load Ajax request handler.
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				new ShipToEcourier\Ajax();
			}

			if ( is_admin() ) {
				// Load admin classes.
				new ShipToEcourier\Admin();
			} else {
				// Load Frontend classes.
				new ShipToEcourier\Frontend();
			}
		}

		/**
		 * Initialize classes
		 *
		 * @return void
		 */
		public function init_classes() {
			$this->container['ecourier'] = new ShipToEcourier\Ecourier_Handler();
		}

		/**
		 * Necessary setup on plugin activation.
		 *
		 * @return void
		 */
		public function activate() {
			$installer = new ShipToEcourier\Installer();
			$installer->run();
		}

	}
}

/**
 * Initialize the main plugin.
 *
 * @return \Ship_To_Ecourier|bool
 */
function ship_to_ecourier() {
	return Ship_To_Ecourier::init();
}

// Kick-off the plugin.
ship_to_ecourier();
