<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.mooberrydreams.com
 * @since      1.0.0
 *
 * @package    Mooberry_Story_Community
 * @subpackage Mooberry_Story_Community/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mooberry_Story_Community
 * @subpackage Mooberry_Story_Community/admin
 * @author     Mooberry Dreams <support@mooberrydreams.com>
 */
class Mooberry_Story_Community_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mooberry-story-community-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'jquery-ui-sortable' );

		wp_enqueue_script( $this->plugin_name . '-common', MOOBERRY_STORY_COMMUNITY_PLUGIN_URL . 'includes/js/mooberry-story-community.js', array(
			'jquery',
		), $this->version, false );

		wp_enqueue_script( $this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'js/mooberry-story-community-admin.js', array(
			'jquery',
			'jquery-ui-dialog',
		), $this->version, false );
		wp_localize_script( $this->plugin_name . '-admin', 'mbdsc_admin_ajax_object', array(
			'ajax_url'             => admin_url( 'admin-ajax.php' ),
			'mbdsc_admin_security' => wp_create_nonce( 'mbdsc_story_cpt_ajax_nonce' ),
			'mbdsc_plugin_url'     => MOOBERRY_STORY_COMMUNITY_PLUGIN_URL,
		) );

	}


	/**
	 * Hook in and register a metabox to handle a theme options page and adds a menu item.
	 */
	public function register_options_metabox() {

		$main_settings_page = new Mooberry_Story_Community_Main_Settings_Page();
		$custom_fields_page = new Mooberry_Story_Community_Custom_Fields_Page();

		//$taxonomies = Mooberry_Story_Community_Custom_Taxonomies_Settings::get_taxonomies();
	}


	public function flush_rewrite_rules() {
		if ( get_option( 'mbdsc_flush_rules', false ) ) {
			flush_rewrite_rules();
			delete_option( 'mbdsc_flush_rules' );
		}
	}

	public function create_pages() {
		$nonce = $_POST['security'];


		// check to see if the submitted nonce matches with the
		// generated nonce we created earlier
		if ( ! wp_verify_nonce( $nonce, 'mbdsc_story_cpt_ajax_nonce' ) ) {
			die ();
		}

		if ( ! isset( $_POST['pages'] ) ) {
			wp_die();
		}

		$pages = $_POST['pages'];
		if ( ! is_array( $pages ) ) {
			$pages = array( $pages );
		}
		// map pages and their shortcodes
		$shortcodes = array(
			'mbdsc_pages_account_settings' => array(
				'title'     => 'Account',
				'shortcode' => MBDSC_ACCOUNT_PAGE_SHORTCODE,
			),
			'mbdsc_pages_edit_story'       => array(
				'title'     => 'Edit Story',
				'shortcode' => MBDSC_EDIT_STORY_PAGE_SHORTCODE,
				'parent'    => 'mbdsc_pages_account_settings',
			),
			'mbdsc_pages_edit_chapter'       => array(
				'title'     => 'Edit Chapter',
				'shortcode' => MBDSC_EDIT_CHAPTER_PAGE_SHORTCODE,
				'parent'    => 'mbdsc_pages_account_settings',
			),

		);

		$results = array();

		foreach ( $pages as $page ) {
			if ( isset( $shortcodes[ $page ] ) ) {
				$parent_id = 0;
				if ( isset( $shortcodes[ $page ]['parent'] ) ) {
					$parent = $shortcodes[ $page ]['parent'];
					if ( array_key_exists( $parent, $results ) ) {
						$parent_id = $results[$parent]['id'];
					} else {
						$parent_id = Mooberry_Story_Community_Main_Settings::get_page( $parent );
					}
				}
				$new_page_id = wp_insert_post( array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_title'   => $shortcodes[ $page ]['title'],
					'post_content' => '[' . $shortcodes[ $page ]['shortcode'] . ']',
					'post_parent'  => $parent_id,
				) );
				if ( ! is_wp_error( $new_page_id ) ) {
					$results[ $page ] = array( 'id' => $new_page_id, 'title' => $shortcodes[ $page ]['title'] );
					Mooberry_Story_Community_Main_Settings::set_page( $page, $new_page_id);
				}
			}
		}


		echo( json_encode( $results ) );

		wp_die();
	}
}
