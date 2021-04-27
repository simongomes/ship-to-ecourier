<?php
/**
 * This file contains the view for STE_Metabox form.
 *
 * @package SendToEcourier\Admin
 */

?>

<div id="ste-metabox-wrap">
	<ul class="ste_metabox submitbox">
		<li class="wide">
			<label for="recipient_name"><?php esc_attr_e( 'Recipient Name', 'ship-to-ecourier' ); ?></label>
			<input class="input-text" type="text" name="recipient_name" id="recipient_name" placeholder="<?php esc_attr__( 'Recipient Name', 'ship-to-ecourier' ); ?>"  value="<?php echo esc_attr( $this->shipping_info['recipient_name'] ); ?>" required>
		</li>
		<li class="wide">
			<label for="recipient_mobile"><?php esc_attr_e( 'Recipient Mobile', 'ship-to-ecourier' ); ?></label>
			<input class="input-text" type="text" name="recipient_mobile" id="recipient_mobile" placeholder="<?php esc_attr__( 'Recipient Mobile', 'ship-to-ecourier' ); ?>"  value="<?php echo esc_attr( $this->shipping_info['recipient_mobile'] ); ?>" required>
		</li>
		<li class="wide">
			<label for="recipient_city"><?php esc_attr_e( 'Recipient City', 'ship-to-ecourier' ); ?></label>
			<input class="input-text" type="text" name="recipient_city" id="recipient_city" placeholder="<?php esc_attr__( 'Recipient City', 'ship-to-ecourier' ); ?>"  value="<?php echo esc_attr( $this->shipping_info['recipient_city'] ); ?>" required>
		</li>
		<li class="wide">
			<label for="recipient_area"><?php esc_attr_e( 'Recipient Area', 'ship-to-ecourier' ); ?></label>
			<input class="input-text" type="text" name="recipient_area" id="recipient_area" placeholder="<?php esc_attr__( 'Recipient Area', 'ship-to-ecourier' ); ?>"  value="<?php echo esc_attr( $this->shipping_info['recipient_area'] ); ?>" required>
		</li>
		<li class="wide">
			<label for="recipient_thana"><?php esc_attr_e( 'Recipient Thana', 'ship-to-ecourier' ); ?></label>
			<input class="input-text" type="text" name="recipient_thana" id="recipient_thana" placeholder="<?php esc_attr__( 'Recipient Thana', 'ship-to-ecourier' ); ?>"  value="<?php echo esc_attr( $this->shipping_info['recipient_thana'] ); ?>" required>
		</li>
		<li class="wide">
			<label for="recipient_zip"><?php esc_attr_e( 'Recipient Zip', 'ship-to-ecourier' ); ?></label>
			<input class="input-text" type="text" name="recipient_zip" id="recipient_zip" placeholder="<?php esc_attr__( 'Recipient Zip', 'ship-to-ecourier' ); ?>"  value="<?php echo esc_attr( $this->shipping_info['recipient_zip'] ); ?>" required>
		</li>
		<li class="wide">
			<label for="recipient_address"><?php esc_attr_e( 'Recipient Address', 'ship-to-ecourier' ); ?></label>
			<textarea class="input-text" name="recipient_address" id="recipient_address" placeholder="<?php esc_attr__( 'Recipient Address', 'ship-to-ecourier' ); ?>" required><?php echo esc_html( $this->shipping_info['recipient_address'] ); ?></textarea>
		</li>
		<li class="wide">
			<label for="payment_method"><?php esc_attr_e( 'Payment Method', 'ship-to-ecourier' ); ?></label>
			<select name="payment_method" id="payment_method">
				<option value="CCRD"><?php esc_html_e( 'Card Payment', 'ship-to-ecourier' ); ?></option>
				<option value="COD" <?php echo 'cod' === $this->shipping_info['payment_method'] ? 'selected' : false; ?>><?php esc_html_e( 'Cash On Delivery', 'ship-to-ecourier' ); ?></option>
				<option value="MPAY"><?php esc_html_e( 'Mobile Payment', 'ship-to-ecourier' ); ?></option>
				<option value="POS"><?php esc_html_e( 'POS', 'ship-to-ecourier' ); ?></option>
			</select>
		</li>
		<li class="wide">
			<label for="package_code"><?php esc_attr_e( 'Package', 'ship-to-ecourier' ); ?></label>
			<select name="package_code" id="package_code">
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
