<?php
/**
 * This file contains the markup for eCourier frontend form and tracking results.
 *
 * @package ShipToEcourier\Frontend
 */

?>

<div id="ept-wrap">
	<h2 class="ept-title"><?php esc_html_e( 'Shipment Tracker', 'ship-to-ecourier' ); ?></h2>
	<h4 class="ept-subtitle"><?php esc_html_e( 'Track your parcel', 'ship-to-ecourier' ); ?></h4>
	<div class="ept-tracker-input-container">
		<form method="post" id="track-form" action="#">
			<input type="text" name="tracking_code" placeholder="<?php esc_attr_e( 'Type your tracking number', 'ship-to-ecourier' ); ?>" class="tracking-code form-control">

<!--			--><?php //wp_nonce_field( 'ste-parcel-tracker-search-form' ); ?>
			<input type="hidden" name="action" value="ste_parcel_tracking_form">

			<button type="submit" class="common-btn">
				<i class="icon-search"></i>
				<span><?php esc_html_e( 'TRACK PARCEL', 'ship-to-ecourier' ); ?></span>
			</button>
		</form>
	</div>
	<div id="error-container">
		<p class="error-message"><?php esc_html_e( 'Tracking number starts with ECR or BL and minimum 11 characters', 'ship-to-ecourier' ); ?></p>
	</div>
	<div id="track-not-found">
		<img src="<?php echo esc_url( STE_ASSETS_URL . '/images/not-found.svg' ); ?>" alt="">
		<h3><?php esc_html_e( 'No Result Found', 'ship-to-ecourier' ); ?></h3>
		<h4><?php esc_html_e( 'We can’t find any results based on your search.', 'ship-to-ecourier' ); ?></h4>
	</div>
	<div id="package-information">
		<div class="info-header">
			<h3><?php esc_html_e( 'Package Information', 'ship-to-ecourier' ); ?></h3>
			<h4><?php esc_html_e( 'Tracking Number', 'ship-to-ecourier' ); ?> <strong class="tracking-number"></strong> </h4>
		</div>
		<div class="track-order-info">
			<ul>
				<li><span><?php esc_html_e( 'Ordered Creation', 'ship-to-ecourier' ); ?> </span> <p class="order-date"></p></li>
				<li><span><?php esc_html_e( 'Elapsed After Order', 'ship-to-ecourier' ); ?> </span> <p class="elapse-time"></p></li>
				<li><span><span><?php esc_html_e( 'Delivery Type', 'ship-to-ecourier' ); ?> </span> Standard</li>
			</ul>
		</div>
		<div class="track-delivery-info">
			<ul>
				<li><span class="company-name"></span></li>
				<li><span class="customer-name"></span> <p class="customer-address"></p></li>
			</ul>
		</div>

		<div class="track-shipment-info">
			<h3><?php esc_html_e( 'Where your shipment has been', 'ship-to-ecourier' ); ?></h3>
			<ul>
				<!-- Shipment statuses will be dynamicaly populated -->
			</ul>
		</div>
	</div>
</div>
