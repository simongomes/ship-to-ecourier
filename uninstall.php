<?php
/**
 * This will be triggered on plugin uninstall. This will remove the eCourier configurations.
 *
 * @package ShipToEcourier
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

// Remove eCourier API credentials.
if ( get_option( 'ste_settings' ) ) {
	delete_option( 'ste_settings' );
}
