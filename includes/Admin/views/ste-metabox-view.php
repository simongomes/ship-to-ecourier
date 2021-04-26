<?php
/**
 * This file contains the view for STE_Metabox form.
 *
 * @package SendToEcourier\Admin
 */
?>

<div id="ste-metabox-wrap">
	<form action="#" id="ste-metabox-form">
		<ul class="ste_metabox submitbox">
			<li class="wide">
				<label for="recipient_name">Recipient Name</label>
				<input class="input-text" type="text" name="recipient_name" id="recipient_name" placeholder="Recipient Name"  value="<?php echo esc_attr( $this->shipping_info['recipient_name'] ); ?>">
			</li>
		</ul>
	</form>
</div>