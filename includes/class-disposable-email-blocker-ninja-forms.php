<?php
/**
 * This file contains the definition of the Disposable_Email_Blocker_Ninja_Forms class, which
 * is used to begin the plugin's functionality.
 *
 * @package       Disposable_Email_Blocker_Ninja_Forms
 * @subpackage    Disposable_Email_Blocker_Ninja_Forms/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since    2.0.0
 */
class Disposable_Email_Blocker_Ninja_Forms {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       Disposable_Email_Blocker_Ninja_Forms_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since     2.0.0
	 * @access    protected
	 * @var       string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function __construct() {
		$this->version     = defined( 'DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_VERSION' ) ? DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_VERSION : '1.0.0';
		$this->plugin_name = 'disposable-email-blocker-ninja-forms';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Disposable_Email_Blocker_Ninja_Forms_Loader. Orchestrates the hooks of the plugin.
	 * - Disposable_Email_Blocker_Ninja_Forms_i18n.   Defines internationalization functionality.
	 * - Disposable_Email_Blocker_Ninja_Forms_Admin.  Defines all hooks for the admin area.
	 * - Disposable_Email_Blocker_Ninja_Forms_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_PATH . 'includes/class-disposable-email-blocker-ninja-forms-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_PATH . 'includes/class-disposable-email-blocker-ninja-forms-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_PATH . 'admin/class-disposable-email-blocker-ninja-forms-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_PATH . 'public/class-disposable-email-blocker-ninja-forms-public.php';

		$this->loader = new Disposable_Email_Blocker_Ninja_Forms_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Disposable_Email_Blocker_Ninja_Forms_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function set_locale() {
		$plugin_i18n = new Disposable_Email_Blocker_Ninja_Forms_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Disposable_Email_Blocker_Ninja_Forms_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'plugin_action_links_' . DISPOSABLE_EMAIL_BLOCKER_NINJA_FORMS_PLUGIN_BASENAME, $plugin_admin, 'add_plugin_action_links' );

		$this->loader->add_action( 'admin_notices', $plugin_admin, 'admin_notices' );

		$this->loader->add_action( 'ninja_forms_form_display_settings', $plugin_admin, 'ninja_forms_form_display_settings' );

		$this->loader->add_action( 'nf_create_disposable_email_domains_table', $plugin_admin, 'create_disposable_email_domains_table' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 */
	private function define_public_hooks() {
		$plugin_public = new Disposable_Email_Blocker_Ninja_Forms_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'ninja_forms_submit_data', $plugin_public, 'ninja_forms_submit_data' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    string The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    Disposable_Email_Blocker_Ninja_Forms_Loader Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
