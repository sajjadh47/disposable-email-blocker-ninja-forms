<?php
/**
 * This file contains the definition of the Disposable_Email_Blocker_Ninja_Forms_Public class, which
 * is used to load the plugin's public-facing functionality.
 *
 * @package       Disposable_Email_Blocker_Ninja_Forms
 * @subpackage    Disposable_Email_Blocker_Ninja_Forms/public
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @since    2.0.0
 */
class Disposable_Email_Blocker_Ninja_Forms_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     string $plugin_name The name of the plugin.
	 * @param     string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Validates email fields in Ninja Forms submissions to block disposable emails.
	 *
	 * This function checks if the submitted email address in a Ninja Forms field (identified by 'email' in the key)
	 * belongs to a list of disposable email domains. It first checks a database table (if it exists)
	 * and then falls back to a text file if the table is not found.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     array $form_data An array containing the submitted form data.
	 * @return    array            The modified form data array, with errors added if disposable emails are found.
	 */
	public function ninja_forms_submit_data( $form_data ) {
		global $wpdb;

		// if not blocking is enabled return early.
		if ( empty( $form_data['settings']['block_disposable_emails'] ) || 1 !== $form_data['settings']['block_disposable_emails'] ) {
			return $form_data;
		}

		if ( isset( $form_data['fields'] ) && ! empty( $form_data['fields'] ) ) {
			$error_msg = empty( $form_data['settings']['disposableEmailFoundMsg'] ) ? __( 'Disposable/Temporary emails are not allowed! Please use a non temporary email', 'disposable-email-blocker-ninja-forms' ) : sanitize_text_field( $form_data['settings']['disposableEmailFoundMsg'] );

			foreach ( $form_data['fields']  as $field_id => $field ) {
				if ( stripos( $field['key'], 'email' ) !== false ) {
					// split on @ and return last value of array (the domain).
					$domain     = explode( '@', sanitize_email( $field['value'] ) );
					$domain     = array_pop( $domain );
					$found      = false;
					$table_name = $wpdb->prefix . DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_TABLE_NAME;
					$txt_file   = DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_PATH . '/public/data/domains.txt';

					// Check if the table exists.
					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
					$table_exists = $wpdb->get_var(
						$wpdb->prepare(
							'SHOW TABLES LIKE %s',
							$table_name
						)
					);

					if ( $table_exists ) {
						// Look for the domain in the database.
						// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
						$found = (bool) $wpdb->get_var(
							$wpdb->prepare(
								// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
								"SELECT COUNT(*) FROM {$table_name} WHERE domain = %s",
								$domain
							)
						);
					} elseif ( file_exists( $txt_file ) ) { // If not found the table and file exists, fall back to txt.
						global $wp_filesystem;

						if ( ! $wp_filesystem ) {
							require_once ABSPATH . 'wp-admin/includes/file.php';
						}

						WP_Filesystem();

						// Get domains list from the txt file.
						$txt_file_content   = $wp_filesystem->get_contents( $txt_file );
						$disposable_domains = explode( "\n", $txt_file_content );

						if ( is_array( $disposable_domains ) && in_array( $domain, $disposable_domains, true ) ) {
							$found = true;
						}
					}

					// If found in DB or txt, invalidate the result.
					if ( $found ) {
						$form_data['errors']['fields'][ $field_id ] = $error_msg;
					}
				}
			}
		}

		return $form_data;
	}
}
