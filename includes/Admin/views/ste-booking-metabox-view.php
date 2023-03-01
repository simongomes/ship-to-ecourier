<?php
/**
 * This file contains the view for STE_Metabox form.
 *
 * @package SendToEcourier\Admin
 */

$recipient_thana     = '';
$recipient_post_code = '';

?>

<div id="ste-metabox-wrap">
	<?php if ( ! $order_shipped ) { ?>
	<div id="ste-booking-metabox-form">
		<ul class="ste_metabox submitbox">
			<li class="wide">
				<label for="recipient_name"><?php esc_attr_e( 'Recipient Name', 'ship-to-ecourier' ); ?></label>
				<input class="input-text" type="text" name="recipient_name" id="recipient_name" placeholder="<?php esc_attr__( 'Recipient Name', 'ship-to-ecourier' ); ?>"  value="<?php echo esc_attr( $this->shipping_info['recipient_name'] ); ?>">
			</li>
			<li class="wide">
				<label for="recipient_mobile"><?php esc_attr_e( 'Recipient Mobile', 'ship-to-ecourier' ); ?></label>
				<input class="input-text" type="text" name="recipient_mobile" id="recipient_mobile" placeholder="<?php esc_attr__( 'Recipient Mobile', 'ship-to-ecourier' ); ?>"  value="<?php echo esc_attr( $this->shipping_info['recipient_mobile'] ); ?>">
			</li>
			<li class="wide">
				<label for="recipient_city"><?php esc_attr_e( 'Recipient District', 'ship-to-ecourier' ); ?></label>
				<select name="recipient_city" id="recipient_city" class="wc-enhanced-select">
					<?php
					foreach ( $cities as $city ) :
						$city_val = strtolower( $city['value'] );
						?>
					<option value="<?php echo $city_val; ?>" <?php echo $city_val === $this->shipping_info['recipient_city'] ? 'selected' : false; ?> ><?php echo $city['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</li>
			<li class="wide">
				<label for="recipient_area"><?php esc_attr_e( 'Recipient Area', 'ship-to-ecourier' ); ?></label>

				<select name="recipient_area" id="recipient_area" class="wc-enhanced-select">
					<?php
					foreach ( $areas as $area ) :
						$area_val = strtolower( $area['name'] );

						if ( $area_val === $this->shipping_info['recipient_area'] ) {
							$recipient_thana     = strtolower( $area['thana'] );
							$recipient_post_code = $area['post_code'];
						}
						?>
						<option
							data-thana="<?php echo $area['thana']; ?>"
							data-post_code="<?php echo $area['post_code']; ?>"
							value="<?php echo $area_val; ?>" <?php echo $area_val === $this->shipping_info['recipient_area'] ? 'selected' : false; ?> >
							<?php echo $area['name']; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</li>
			<li class="wide">
				<label for="recipient_thana"><?php esc_attr_e( 'Recipient Thana', 'ship-to-ecourier' ); ?></label>
				<input class="input-text" type="text" name="recipient_thana" id="recipient_thana" placeholder="<?php esc_attr__( 'Recipient Thana', 'ship-to-ecourier' ); ?>"  value="<?php echo esc_attr( $recipient_thana ); ?>" readonly>
			</li>
			<li class="wide">
				<label for="recipient_zip"><?php esc_attr_e( 'Recipient Zip', 'ship-to-ecourier' ); ?></label>
				<input class="input-text" type="text" name="recipient_zip" id="recipient_zip" placeholder="<?php esc_attr__( 'Recipient Zip', 'ship-to-ecourier' ); ?>"  value="<?php echo esc_attr( $recipient_post_code ); ?>" readonly>
			</li>
			<li class="wide">
				<label for="recipient_address"><?php esc_attr_e( 'Recipient Address', 'ship-to-ecourier' ); ?></label>
				<textarea class="input-text" name="recipient_address" id="recipient_address" placeholder="<?php esc_attr__( 'Recipient Address', 'ship-to-ecourier' ); ?>" required><?php echo esc_html( $this->shipping_info['recipient_address'] ); ?></textarea>
			</li>
			<li class="wide">
				<label for="payment_method"><?php esc_attr_e( 'Payment Method', 'ship-to-ecourier' ); ?></label>
				<select name="payment_method" id="payment_method" class="wc-enhanced-select">
					<option value="CCRD" <?php echo 'ccrd' === $this->shipping_info['payment_method'] ? 'selected' : false; ?>><?php esc_html_e( 'Card Payment', 'ship-to-ecourier' ); ?></option>
					<option value="COD" <?php echo 'cod' === $this->shipping_info['payment_method'] ? 'selected' : false; ?>><?php esc_html_e( 'Cash On Delivery', 'ship-to-ecourier' ); ?></option>
					<option value="MPAY" <?php echo 'mpay' === $this->shipping_info['payment_method'] ? 'selected' : false; ?>><?php esc_html_e( 'Mobile Payment', 'ship-to-ecourier' ); ?></option>
					<option value="POS" <?php echo 'pos' === $this->shipping_info['payment_method'] ? 'selected' : false; ?>><?php esc_html_e( 'POS', 'ship-to-ecourier' ); ?></option>
				</select>
			</li>
			<li class="wide">
				<label for="package_code"><?php esc_attr_e( 'Package', 'ship-to-ecourier' ); ?></label>
				<select name="package_code" id="package_code" class="wc-enhanced-select">
					<?php
					foreach ( $this->shipping_info['package_code'] as $package ) {
						?>
						<option value="<?php echo esc_attr( $package->package_code ); ?>"><?php echo esc_html( $package->package_name ); ?></option>
						<?php
					}
					?>
				</select>
			</li>
			<li class="wide error">
				<p class="error-message"></p>
			</li>
			<li class="wide">
				<?php submit_button( __( 'Submit', 'ship-to-ecourier' ), 'primary', 'submit_ste_ecourier_parcel', false ); ?>
			</li>

		</ul>
		<input type="hidden" name="product_id" id="product_id" value="<?php echo esc_attr( $this->shipping_info['product_id'] ); ?>">
		<input type="hidden" name="product_price" id="product_price" value="<?php echo esc_attr( $this->shipping_info['product_price'] ); ?>">
		<input type="hidden" name="number_of_item" id="number_of_item" value="<?php echo esc_attr( $this->shipping_info['number_of_item'] ); ?>">
		<input type="hidden" name="comments" id="comments" value="<?php echo esc_attr( $this->shipping_info['comments'] ); ?>">
	</div>
	<?php } ?>
	<div id="ste-booking-metabox-message" <?php if ( $order_shipped ) { ?>
		style="display: block"
	<?php } ?>
	>
		<h3 class="title">
			<?php if ( $order_shipped ) { ?>
				<?php esc_html_e( 'Shipped On ', 'ship-to-ecourier' ); ?> <?php echo esc_html( gmdate( 'd F, Y', strtotime( $order_shipped->created_at ) ) ); ?>
			<?php } ?>
		</h3>
		<?php if ( $order_shipped ) { ?>
			<h4>
			<?php esc_html_e( 'Shipped By ', 'ship-to-ecourier' ); ?> <?php echo esc_html( $order_shipped->user ); ?>
			</h4>
		<?php } ?>
		<h4>
			<?php esc_html_e( 'Tracking ID: ', 'ship-to-ecourier' ); ?>
			<span class="tracking_id">
				<?php if ( $order_shipped ) { ?>
					<?php echo esc_html( $order_shipped->tracking_id ); ?>
				<?php } ?>
			</span>
		</h4>
	</div>

	<p class="error-message"></p>

	<?php if ( $order_shipped ) { ?>
		<div id="ste-metabox-actions">
			<button id="ste-print-label" class="button button-primary" value="<?php echo esc_html( $order_shipped->tracking_id ); ?>">Print Label</button>
			<button id="ste-cancel-order" class="button button-cancel" value="<?php echo esc_html( $order_shipped->tracking_id ); ?>">Cancel Order</button>
		</div>
	<?php } ?>

	<input type="hidden" name="original_order_number" id="original_order_number" value="<?php echo esc_attr( $post->ID ); ?>">
</div>
