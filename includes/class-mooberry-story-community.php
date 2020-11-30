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
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
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
		$this->perform_updates();
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
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'admin/setting-pages/class-custom-field-options-tab.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'admin/setting-pages/class-taxonomy-fields-tab.php';

		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/settings/class-settings.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/settings/class-main-settings.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/settings/class-custom-field.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/settings/class-custom-field-option.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/settings/class-custom-fields-settings.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/settings/class-custom-taxonomy.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/settings/class-custom-taxonomies-settings.php';


		// CPTs
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/class-custom-post-types.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/class-post-object.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/stories/class-story-cpt.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/chapters/class-chapter-cpt.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/reviews/class-review-cpt.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/class-taxonomy.php';

		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/stories/class-story.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/stories/class-story-collection.php';

		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/chapters/class-chapter.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/chapters/class-chapter-collection.php';

		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/authors/class-author.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/authors/class-author-cpt.php';

		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/reviews/class-review.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/reviews/class-review-collection.php';

		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'public/widgets/class-updated-stories-widget.php';
		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'public/widgets/class-taxonomy-widget.php';
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
	 * Runs any updates needed.
	 *
	 * Uses the Mooberry_Story_Community_Updates class in order to check for and run updates
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function perform_updates() {

		require_once MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'includes/class-updates.php';

		$plugin_updates = new Mooberry_Story_Community_Updates( $this->version );

		add_action( 'init', array( $plugin_updates, 'check_for_updates' ), 99 );

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

		add_action( 'admin_init', array( $plugin_admin, 'flush_rewrite_rules' ) );
		add_action( 'cmb2_admin_init', array( $plugin_admin, 'register_options_metabox' ) );
		add_action( 'wp_ajax_mbdsc_create_pages', array( $plugin_admin, 'create_pages' ) );

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

		//add_filter( 'the_content', array( $plugin_public, 'author_profile_page' ) );

		add_action( 'user_register', array( $plugin_public, 'add_new_user' ), 10, 1 );
		add_action( 'cmb2_after_init', array( $plugin_public, 'handle_frontend_new_post_submission' ) );


		// shortcodes
		add_shortcode( 'mbdsc_title', array( $plugin_public, 'shortcode_title' ) );
		add_shortcode( 'mbdsc_author', array( $plugin_public, 'shortcode_author' ) );
		add_shortcode( 'mbdsc_cover', array( $plugin_public, 'shortcode_cover' ) );
		add_shortcode( 'mbdsc_summary', array( $plugin_public, 'shortcode_summary' ) );
		add_shortcode( 'mbdsc_complete', array( $plugin_public, 'shortcode_complete' ) );
		add_shortcode( 'mbdsc_story_word_count', array( $plugin_public, 'shortcode_story_word_count' ) );
		add_shortcode( 'mbdsc_custom_field_story', array( $plugin_public, 'shortcode_custom_field_story' ) );
		add_shortcode( 'mbdsc_custom_field_chapter', array( $plugin_public, 'shortcode_custom_field_chapter' ) );
		add_shortcode( 'mbdsc_taxonomy_field', array( $plugin_public, 'shortcode_taxonomy_field' ) );
		add_shortcode( 'mbdsc_toc', array( $plugin_public, 'shortcode_toc' ) );
		add_shortcode( 'mbdsc_toc_link', array( $plugin_public, 'shortcode_toc_link' ) );
		add_shortcode( 'mbdsc_prev', array( $plugin_public, 'shortcode_prev' ) );
		add_shortcode( 'mbdsc_next', array( $plugin_public, 'shortcode_next' ) );
		add_shortcode( 'mbdsc_author_pic', array( $plugin_public, 'shortcode_author_pic' ) );
		add_shortcode( 'mbdsc_author_bio', array( $plugin_public, 'shortcode_author_bio' ) );
		add_shortcode( 'mbdsc_author_stories', array( $plugin_public, 'shortcode_author_stories' ) );
		add_shortcode( 'mbdsc_review_form', array( $plugin_public, 'shortcode_review_form' ) );
		add_shortcode( 'mbdsc_reviews', array( $plugin_public, 'shortcode_chapter_reviews' ) );
		add_shortcode( 'mbdsc_review', array( $plugin_public, 'shortcode_review' ) );
		add_shortcode( 'mbdsc_review_count', array( $plugin_public, 'shortcode_chapter_review_count' ) );

		add_shortcode( MBDSC_ACCOUNT_PAGE_SHORTCODE, array( $plugin_public, 'shortcode_account_page'));
		add_shortcode( MBDSC_EDIT_STORY_PAGE_SHORTCODE, array( $plugin_public, 'shortcode_edit_story_page'));

	}


	private function register_cpts() {
		$story   = new Mooberry_Story_Community_Story_CPT();
		$chapter = new Mooberry_Story_Community_Chapter_CPT();
		$user    = new Mooberry_Story_Community_Author_CPT();
		$review  = new Mooberry_Story_Community_Review_CPT();
	}


	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

}
