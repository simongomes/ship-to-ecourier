<?php // phpcs:ignore
/**
 * This file registers custom order status `Shipped` for WC orders.
 *
 * @package ShipToEcourier\Admin
 */
namespace ShipToEcourier\Admin;

if ( ! class_exists( 'STE_Order_Status_Shipped' ) ) {
	/**
	 * Class STE_Order_Status_Shipped
	 *
	 * The class registers the `Shipped` order status for WC orders.
	 *
	 * @package ShipToEcourier\Admin
	 */
	class STE_Order_Status_Shipped {

		/**
		 * STE_Order_Status_Shipped constructor.
		 *
		 * Add necessary actions to register `Shipped` order status.
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_shipped_order_status' ) );
			add_filter( 'wc_order_statuses', array( $this, 'add_shipped_to_order_statuses' ) );
		}

		/**
		 * Register new post status `Shipped` for WC orders.
		 *
		 * @return void
		 */
		public function register_shipped_order_status() {
			register_post_status(
				'wc-shipped',
				array(
					'label'                     => 'Shipped',
					'public'                    => true,
					'show_in_admin_status_list' => true,
					'show_in_admin_all_list'    => true,
					'exclude_from_search'       => false,
					'label_count'               => _n_noop( 'Shipped <span class="count">(%s)</span>', 'Shipped <span class="count">(%s)</span>' ), // phpcs:ignore
				)
			);
		}

		/**
		 * Add `Shipped` post status to WC order status list.
		 *
		 * @param mixed $order_statuses WC Order statuses.
		 *
		 * @return array
		 */
		public function add_shipped_to_order_statuses( $order_statuses ) {
			$new_order_statuses = array();
			foreach ( $order_statuses as $key => $status ) {
				$new_order_statuses[ $key ] = $status;
				if ( 'wc-processing' === $key ) {
					$new_order_statuses['wc-shipped'] = 'Shipped';
				}
			}
			return $new_order_statuses;
		}
	}
}