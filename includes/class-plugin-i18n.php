<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      2.0.0
 * @package    Disposable_Email_Blocker_Ninja_Forms
 * @subpackage Disposable_Email_Blocker_Ninja_Forms/includes
 * @author     Sajjad Hossain Sagor <sagorh672@gmail.com>
 */
class Disposable_Email_Blocker_Ninja_Forms_i18n
{
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    2.0.0
	 * @access   public
	 */
	public function load_plugin_textdomain()
	{
		load_plugin_textdomain(
			'disposable-email-blocker-ninja-forms',
			false,
			dirname( DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_BASENAME ) . '/languages/'
		);
	}
}
