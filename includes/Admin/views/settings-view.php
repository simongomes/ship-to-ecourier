<?php
/**
 * This file contains the markup and functionalities for eCourier API settings view.
 *
 * @package ShipToEcourier
 */

?>

<div class="wrap">
	<h2 class="wp-heading-inline"><?php esc_html_e( 'eCourier API Credentials', 'ship-to-ecourier' ); ?></h2>

	<?php if ( ! empty( $this->errors ) ) { ?>
		<div id="setting-error-settings_updated" class="notice notice-error settings-error">
			<p><strong><?php echo esc_html( $this->errors['required-field-missing'] ); ?></strong></p></div>
	<?php } ?>

	<form action="" method="post">
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row">
					<label for="user_id">USER-ID:</label>
				</th>
				<td>
					<input type="text" name="user_id" id="user_id" class="regular-text" value="<?php echo isset( $this->config['user_id'] ) ? esc_attr( $this->config['user_id'] ) : ''; ?>">
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="api_key">API-KEY:</label>
				</th>
				<td>
					<input type="text" name="api_key" id="api_key" class="regular-text" value="<?php echo isset( $this->config['api_key'] ) ? esc_attr( $this->config['api_key'] ) : ''; ?>">
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="api_secret">API-SECRET:</label>
				</th>
				<td>
					<input type="text" name="api_secret" id="api_secret" class="regular-text" value="<?php echo isset( $this->config['api_secret'] ) ? esc_attr( $this->config['api_secret'] ) : ''; ?>">
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="api_environment">Environment:</label>
				</th>
				<td>
					<?php $selected_environment = isset( $this->config['api_environment'] ) ? esc_attr( $this->config['api_environment'] ) : ''; ?>

					<select name="api_environment" id="api_environment" class="regular-text">
						<option value="staging" <?php echo 'staging' === $selected_environment ? 'selected' : false; ?>>Staging</option>
						<option value="live" <?php echo 'live' === $selected_environment ? 'selected' : false; ?>>Live</option>
					</select>
				</td>
			</tr>
			</tbody>
		</table>
		<?php wp_nonce_field( 'ste-ecourier-settings' ); ?>
		<?php submit_button( __( 'Save Settings', 'ship-to-ecourier' ), 'primary', 'submit_ste_ecourier_settings', true, null ); ?>
	</form>

	<h1 class="wp-heading-inline"><?php esc_html_e( 'eCourier Parcel Tracker Shortcode', 'ship-to-ecourier' ); ?></h1>

	<div class="card">
		<h2 class="title"><?php esc_html_e( 'Copy shortcode and place it to any of your pages.', 'ship-to-ecourier' ); ?></h2>
		<h4><?php esc_html_e( 'From Dashboard:', 'ship-to-ecourier' ); ?></h4>
		<code><?php esc_html_e( '[ecourier-parcel-tracker]', 'ship-to-ecourier' ); ?></code>

		<h4><?php esc_html_e( 'From PHP file:', 'ship-to-ecourier' ); ?></h4>
		<code><?php echo esc_html( "<?php echo do_shortcode( 'ecourier-parcel-tracker' ); ?>" ); ?></code>
		<br><br>
	</div>
</div>
