<?php // phpcs:ignore
/**
 * This file contains class that handles the custom Ship To eCourier Metabox.
 *
 * @package ShipToEcourier\Admin
 */

namespace ShipToEcourier\Admin;

if ( ! class_exists( 'STE_Metabox' ) ) {
	/**
	 * Class STE_Metabox
	 *
	 * STE_Metabox registers and handles the custom Ship To eCourier metabox to send parcel booking request to eCourier.
	 *
	 * @package ShipToEcourier\Admin
	 */
	class STE_Metabox {

		/**
		 * Holds all shipping information for eCourier.
		 *
		 * @var array
		 */
		private $shipping_info = array();

		/**
		 * STE_Metabox constructor, fires action to add the metabox.
		 *
		 * @return void;
		 */
		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'register_metabox' ) );
		}

		/**
		 * The method handles the metabox registration.
		 *
		 * @return void;
		 */
		public function register_metabox() {
			add_meta_box( 'ste-booking-metabox', __( 'Ship To eCourier', 'send-to-ecourier' ), array( $this, 'metabox_view_handler' ), 'shop_order', 'side' );
		}

		/**
		 * The method is a handler for STE_Metabox.
		 *
		 * @return void
		 */
		public function metabox_view_handler() {
			global $theorder, $post;

			if ( ! is_object( $theorder ) ) {
				$theorder = wc_get_order( $post->ID );
			}

			// Load admin assets.
			wp_enqueue_style( 'ste-admin-styles' );
			wp_enqueue_script( 'ste-admin-script' );

			$order_shipped = ste_get_order_shipping_info( $theorder->get_order_number() );

			if ( ! $order_shipped ) {
				// Set all necessary Shipping Information.
				$this->set_shipping_info( $theorder );
			} else {
				$order_shipped->user = get_user_by( 'ID', $order_shipped->created_by )->display_name;
			}

			// Load the parcel booking form/view.
			if ( ! file_exists( __DIR__ . '/views/ste-booking-metabox-view.php' ) ) {
				return;
			}
			include __DIR__ . '/views/ste-booking-metabox-view.php';
		}

		/**
		 * Set the shipping info to $shipping_info variable to access from the view.
		 *
		 * @param \WC_Order $order will hold the WooCommerce Order object.
		 *
		 * @return void
		 */
		public function set_shipping_info( \WC_Order $order ) {
			$this->shipping_info['recipient_name']    = '' !== trim( $order->get_formatted_shipping_full_name() ) ? $order->get_formatted_shipping_full_name() : $order->get_formatted_billing_full_name();
			$this->shipping_info['recipient_mobile']  = $order->get_billing_phone();
			$this->shipping_info['recipient_city']    = '' !== trim( $order->get_shipping_city() ) ? $order->get_shipping_city() : $order->get_billing_city();
			$this->shipping_info['recipient_area']    = '';
			$this->shipping_info['recipient_thana']   = '';
			$this->shipping_info['recipient_zip']     = '' !== trim( $order->get_shipping_postcode() ) ? $order->get_shipping_postcode() : $order->get_billing_postcode();
			$this->shipping_info['recipient_address'] = '' !== trim( $order->get_shipping_address_1() ) ? $order->get_shipping_address_1() . '' . $order->get_shipping_address_2() : $order->get_billing_address_1() . ' ' . $order->get_billing_address_2();
			$this->shipping_info['product_id']        = $order->get_order_number();
			$this->shipping_info['product_price']     = $order->get_total();
			$this->shipping_info['number_of_item']    = $order->get_item_count();
			$this->shipping_info['payment_method']    = $order->get_payment_method();
			$this->shipping_info['package_code']      = ste_get_ecourier_packages();
			$this->shipping_info['comments']          = '';

			foreach ( $order->get_items() as $item ) {
				$this->shipping_info['comments'] .= $item->get_name() . ' x' . $item->get_quantity() . PHP_EOL;
			}
		}

	}
}
