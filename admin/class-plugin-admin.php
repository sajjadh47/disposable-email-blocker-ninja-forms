<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @package    Disposable_Email_Blocker_Ninja_Forms
 * @subpackage Disposable_Email_Blocker_Ninja_Forms/admin
 * @author     Sajjad Hossain Sagor <sagorh672@gmail.com>
 */
class Disposable_Email_Blocker_Ninja_Forms_Admin
{
	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name     The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    		The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @access   public
	 * @param    string    $plugin_name     The name of this plugin.
	 * @param    string    $version    		The version of this plugin.
	 */
	public function __construct( $plugin_name, $version )
	{
		$this->plugin_name 	= $plugin_name;
		
		$this->version 		= $version;
	}

	/**
	 * Displays admin notices in the admin area.
	 *
	 * This function checks if the required plugin is active.
	 * If not, it displays a warning notice and deactivates the current plugin.
	 *
	 * @since    2.0.0
	 * @access   public
	 */
	public function admin_notices()
	{
		// Check if required plugin is active.
		if ( ! is_plugin_active( 'ninja-forms/ninja-forms.php' ) )
		{
			echo '<div class="notice notice-warning is-dismissible">';
			
				printf(
					wp_kses_post(
					__( '<p>Disposable Email Blocker - Ninja Forms requires <a href="%s">Ninja Forms</a> plugin to be active!</p>', 'disposable-email-blocker-ninja-forms' )
					),
					esc_url( 'https://wordpress.org/plugins/ninja-forms/' )
				);
			
			echo '</div>';

			// Deactivate the plugin
			deactivate_plugins( DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_BASENAME );
		}
	}

	/**
	 * Handles plugin table creation task.
	 *
	 * This function is called when the plugin is activated using cron. It creates the
	 * necessary database table to store disposable email domains and populates it
	 * with data from a txt file.
	 *
	 * @since    2.0.0
	 * @access   public
	 * @return   void
	 */
	public function create_disposable_email_domains_table()
	{
		global $wpdb;
		
		$table_name 				= $wpdb->prefix . DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_TABLE_NAME;

		$txt_file 					= DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_PATH . '/admin/data/domains.txt';

		if ( ! file_exists( $txt_file ) ) return;
		
		// Create table if it doesn't exist
		$charset_collate 			= $wpdb->get_charset_collate();
		
		$sql 						= 
		"CREATE TABLE IF NOT EXISTS $table_name (
			id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			domain VARCHAR(255) NOT NULL UNIQUE
		) $charset_collate;";
		
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		
		dbDelta( $sql );

		// Get domains list from txt file
		$disposable_domains 		= explode( "\n", file_get_contents( $txt_file ) );

		if ( ! empty( $disposable_domains ) && is_array( $disposable_domains ) )
		{
			foreach ( $disposable_domains as $domain )
			{
				// Insert or update domains
				$wpdb->replace(
					$table_name,
					[ 'domain' => sanitize_text_field( $domain ) ],
					[ '%s' ]
				);
			}
		}
	}

	/**
	 * Adds custom settings to the Ninja Forms form display settings for disposable email blocking.
	 *
	 * This function adds a new section to the Ninja Forms form display settings, allowing users
	 * to customize the error message displayed when a disposable email is detected and
	 * enable/disable the blocking of disposable emails for a specific form.
	 *
	 * @since    2.0.0
	 * @access   public
	 * @param    array $message An array of existing form display settings.
	 * @return   array 			The modified array of form display settings.
	 */
	public function ninja_forms_form_display_settings( $message )
	{
		$message['custom_messages']['settings'][] = array(
			'name' 			=> 'disposableEmailFoundMsg',
			'type' 			=> 'textbox',
			'label' 		=> esc_html__( 'Message when a disposable/temporary Email found', 'disposable-email-blocker-ninja-forms' ),
			'width' 		=> 'full',
			'placeholder' 	=> __( 'Disposable/Temporary emails are not allowed! Please use a non temporary email', 'disposable-email-blocker-ninja-forms' )
		);

		$message['custom_messages']['settings'][] = array(
			'name' 		=> 'block_disposable_emails',
			'type' 		=> 'toggle',
			'label' 	=> esc_html__( 'Block Disposable/Temporary Emails', 'disposable-email-blocker-ninja-forms' ),
			'width' 	=> 'full',
			'group' 	=> 'primary',
			'value' 	=> 1,
		);

		return $message;
	}
}
