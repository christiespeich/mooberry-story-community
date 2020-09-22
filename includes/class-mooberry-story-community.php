<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.mooberrydreams.com
 * @since      1.0.0
 *
 * @package    Mooberry_Story_Community
 * @subpackage Mooberry_Story_Community/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Mooberry_Story_Community
 * @subpackage Mooberry_Story_Community/includes
 * @author     Mooberry Dreams <support@mooberrydreams.com>
 */
class Mooberry_Story_Community {


	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MOOBERRY_STORY_COMMUNITY_VERSION' ) ) {
			$this->version = MOOBERRY_STORY_COMMUNITY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'mooberry-story-community';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->register_cpts();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mooberry_Story_Community_i18n. Defines internationalization functionality.
	 * - Mooberry_Story_Community_Admin. Defines all hooks for the admin area.
	 * - Mooberry_Story_Community_Public. Defines all hooks for the public side of the site.
	 *
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/class-mooberry-story-community-i18n.php';


		if ( file_exists( MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/vendor/CMB2/init.php' ) ) {
			require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/vendor/CMB2/init.php';
		}

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'admin/class-mooberry-story-community-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'public/class-mooberry-story-community-public.php';


		// settings
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'admin/setting-pages/class-settings-page.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'admin/setting-pages/class-tabbed-settings-page.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'admin/setting-pages/class-settings-tab.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'admin/setting-pages/class-main-settings-page.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'admin/setting-pages/class-custom-fields-settings-page.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'admin/setting-pages/class-custom-fields-tab.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'admin/setting-pages/class-taxonomy-fields-tab.php';

		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/settings/class-settings.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/settings/class-main-settings.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/settings/class-custom-fields-settings.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/settings/class-custom-taxonomies-settings.php';


		// CPTs
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/class-custom-post-types.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/stories/class-story-cpt.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/chapters/class-chapter-cpt.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/class-taxonomy.php';

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mooberry_Story_Community_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Mooberry_Story_Community_i18n();

		add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Mooberry_Story_Community_Admin( $this->get_plugin_name(), $this->get_version() );

		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ) );

		add_action( 'cmb2_admin_init', array( $plugin_admin, 'register_options_metabox' ) );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Mooberry_Story_Community_Public( $this->get_plugin_name(), $this->get_version() );

		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_scripts' ) );

	}

	private function register_cpts() {
		$story = new Mooberry_Story_Community_Story_CPT();
		$chapter = new Mooberry_Story_Community_Chapter_CPT();

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
