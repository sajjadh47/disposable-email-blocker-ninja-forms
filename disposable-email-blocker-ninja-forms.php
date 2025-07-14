<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           Disposable_Email_Blocker_Ninja_Forms
 * @author            Sajjad Hossain Sagor <sagorh672@gmail.com>
 *
 * Plugin Name:       Disposable Email Blocker - Ninja Forms
 * Plugin URI:        https://wordpress.org/plugins/disposable-email-blocker-ninja-forms/
 * Description:       Block Spammy Disposable/Temporary Emails From Submitting On Ninja Forms.
 * Version:           2.0.2
 * Requires at least: 5.6
 * Requires PHP:      8.0
 * Author:            Sajjad Hossain Sagor
 * Author URI:        https://sajjadhsagor.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       disposable-email-blocker-ninja-forms
 * Domain Path:       /languages
 * Requires Plugins:  ninja-forms
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_VERSION', '2.0.2' );

/**
 * Define Plugin Folders Path
 */
define( 'DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

define( 'DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Plugin database table name.
define( 'DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_TABLE_NAME', 'nf_disposable_domains' );

// Plugin db version.
define( 'DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_DB_VERSION', '20251705' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-disposable-email-blocker-ninja-forms-activator.php
 *
 * @since    2.0.0
 */
function on_activate_disposable_email_blocker_ninja_forms() {
	require_once DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_PATH . 'includes/class-disposable-email-blocker-ninja-forms-activator.php';

	Disposable_Email_Blocker_Ninja_Forms_Activator::on_activate();
}

register_activation_hook( __FILE__, 'on_activate_disposable_email_blocker_ninja_forms' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-disposable-email-blocker-ninja-forms-deactivator.php
 *
 * @since    2.0.0
 */
function on_deactivate_disposable_email_blocker_ninja_forms() {
	require_once DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_PATH . 'includes/class-disposable-email-blocker-ninja-forms-deactivator.php';

	Disposable_Email_Blocker_Ninja_Forms_Deactivator::on_deactivate();
}

register_deactivation_hook( __FILE__, 'on_deactivate_disposable_email_blocker_ninja_forms' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 *
 * @since    2.0.0
 */
require DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_PATH . 'includes/class-disposable-email-blocker-ninja-forms.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_disposable_email_blocker_ninja_forms() {
	$plugin = new Disposable_Email_Blocker_Ninja_Forms();

	$plugin->run();
}

run_disposable_email_blocker_ninja_forms();
