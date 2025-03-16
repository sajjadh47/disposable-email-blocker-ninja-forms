<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             2.0.0
 * @package           Disposable_Email_Blocker_Ninja_Forms
 *
 * Plugin Name:       Disposable Email Blocker - Ninja Forms
 * Plugin URI:        https://wordpress.org/plugins/disposable-email-blocker-ninja-forms/
 * Description:       Block Spammy Disposable-Temporary Emails To Submit On Ninja Forms.
 * Version:           2.0.0
 * Author:            Sajjad Hossain Sagor
 * Author URI:        https://sajjadhsagor.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       disposable-email-blocker-ninja-forms
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;

/**
 * Currently plugin version.
 */
define( 'DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_VERSION', '2.0.0' );

/**
 * Define Plugin Folders Path
 */
define( 'DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

define( 'DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Plugin database table name to add domains list
define( 'DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_TABLE_NAME', 'disposable_domains' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-activator.php
 * 
 * @since    2.0.0
 */
function activate_disposable_email_blocker_ninja_forms()
{
	require_once DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_PATH . 'includes/class-plugin-activator.php';
	
	Disposable_Email_Blocker_Ninja_Forms_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_disposable_email_blocker_ninja_forms' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-deactivator.php
 * 
 * @since    2.0.0
 */
function deactivate_disposable_email_blocker_ninja_forms()
{
	require_once DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_PATH . 'includes/class-plugin-deactivator.php';
	
	Disposable_Email_Blocker_Ninja_Forms_Deactivator::deactivate();
}

register_deactivation_hook( __FILE__, 'deactivate_disposable_email_blocker_ninja_forms' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 * 
 * @since    2.0.0
 */
require DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_PATH . 'includes/class-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_disposable_email_blocker_ninja_forms()
{
	$plugin = new Disposable_Email_Blocker_Ninja_Forms();
	
	$plugin->run();
}

run_disposable_email_blocker_ninja_forms();
