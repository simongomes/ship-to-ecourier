<?php // phpcs:ignore
/**
 * This file contains the Settings class,
 * which is responsible for handling settings screen and configuration form.
 *
 * @package ShipToEcourier\Admin
 */
namespace ShipToEcourier\Admin;

if ( ! class_exists( 'Settings' ) ) {
	/**
	 * Class Settings
	 *
	 * Settings class handles the settings view and API configuration form.
	 *
	 * @package ShipToEcourier\Admin
	 */
	class Settings {

		/**
		 * Settings form errors.
		 *
		 * @var array
		 */
		public $errors = array();

		/**
		 * Holds the API configurations stored in database.
		 *
		 * @var array
		 */
		public $config = array();

		/**
		 * Handles the eCourier API configuration page.
		 *
		 * @return void
		 */
		public function load_settings_page() {
			if ( ! file_exists( __DIR__ . '/views/settings-view.php' ) ) {
				return;
			}
			include __DIR__ . '/views/settings-view.php';
		}

		/**
		 * Handle the eCourier API settings form submissions.
		 *
		 * @return void
		 */
		public function ste_settings_form_handler() {
			if ( ! isset( $_POST['submit_ste_ecourier_settings'] ) ) {
				return;
			}

			if ( isset( $_REQUEST['_wpnonce'] ) && ! wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'ste-ecourier-settings' ) ) {
				wp_die( 'Nope! I can\'t let you do this' );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'I don\'t think you should be doing this! I see no permission!' );
			}

			$user_id         = isset( $_POST['user_id'] ) ? sanitize_text_field( wp_unslash( $_POST['user_id'] ) ) : '';
			$api_key         = isset( $_POST['api_key'] ) ? sanitize_text_field( wp_unslash( $_POST['api_key'] ) ) : '';
			$api_secret      = isset( $_POST['api_secret'] ) ? sanitize_text_field( wp_unslash( $_POST['api_secret'] ) ) : '';
			$api_environment = isset( $_POST['api_environment'] ) ? sanitize_text_field( wp_unslash( $_POST['api_environment'] ) ) : 'staging';

			// Handle required field errors.
			if ( empty( $user_id ) || empty( $api_key ) || empty( $api_secret ) || empty( $api_environment ) ) {
				$this->errors['required-field-missing'] = __( 'All fields are required.', 'ecourier-parcel-tracker' );
			}

			if ( ! empty( $this->errors ) ) {
				return;
			}

			$result = ste_insert_settings(
				array(
					'user_id'         => $user_id,
					'api_key'         => $api_key,
					'api_secret'      => $api_secret,
					'api_environment' => $api_environment,
				)
			);

			if ( is_wp_error( $result ) ) {
				wp_die( wp_kses_post( $result->get_error_message() ) );
			}

			$redirect_to = admin_url( 'admin.php?page=ship-to-ecourier&inserted=true' );

			wp_safe_redirect( $redirect_to );

			exit;
		}

		/**
		 * Get eCourier API configuration from database
		 *
		 * @return void
		 */
		public function ste_get_api_configurations() {
			$this->config = ste_get_settings();
		}
	}
}
