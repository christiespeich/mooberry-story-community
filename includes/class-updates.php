<?php
/**
 * Runs plugin updates
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mooberry_Story_Community
 */

/**
 * Runs plugin updates
 *
 * See if the plugin needs to run updates. Update the version stored
 * in the database after updates are finished
 *
 * @package    Mooberry_Story_Community
 * @author     Mooberry Dreams <mooberrydreams@mooberrydreams.com>
 */
class Mooberry_Story_Community_Updates {

	/**
	 * The current version of the plugin
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_version The current version of the plugin
	 */
	private $plugin_version;

	/**
	 * The version of the plugin installed on the server before updating
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $installed_version The previous version of the plugin
	 */
	private $installed_version;

	protected $admin_manager;
	protected $expiration_job_update_process;


	/**
	 * Initialize the current version of the plugin and the current version in the database
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_version ) {
		$this->plugin_version = $plugin_version;

		$this->installed_version = get_option( MOOBERRY_STORY_COMMUNITY_PLUGIN_VERSION_KEY, '0.1' );


	}


	/**
	 * Run update routines and then update the database
	 * with the current version number
	 *
	 * @since    1.0.0
	 */
	public function check_for_updates() {
		$this->run();

		if ( $this->installed_version != $this->plugin_version ) {
			$this->update_version();
		}
	}


	/**
	 * Run update routines for any version that needs them
	 *
	 * @since    1.0.0
	 */
	private function run() {

		// add a call to check_for_update for each version that needs update script run
		// then add a function update_to_{version_number} where . are replaced with _

		$this->run_update( '0.3' );

	}

	/**
	 * Update the version stored in the database to reflect that the update scripts have run
	 *
	 * @since    1.0.0
	 */
	private function update_version() {
		update_option( MOOBERRY_STORY_COMMUNITY_PLUGIN_VERSION_KEY, $this->plugin_version );
		$this->installed_version = $this->plugin_version;
	}

	/**
	 * If the installed version is lower than the current version, run an update
	 *
	 * @param   $version    string      the newer version that requires update scripts to run
	 *
	 * @since    1.0.0
	 *
	 */
	private function run_update( $version ) {

		if ( version_compare( $this->installed_version, $version, '<' ) ) {
			// ex, update_to_1_1
			$function_name = 'update_to_' . str_replace( '.', '_', $version );
			if ( method_exists( $this, $function_name ) ) {
				call_user_func( array( $this, $function_name ) );
			}
		}

	}

	// add update functions as needed called update_to_{version_number} where . are replaced with _

	private function update_to_0_3() {
		$author_cpt = new Mooberry_Story_Community_Author_CPT();
		$review_cpt = new Mooberry_Story_Community_Review_CPT();
		$cpts        = array( $author_cpt, $review_cpt );
		foreach ( $cpts as $cpt ) {
			$cpt->register();
			$cpt->set_up_roles();
		}



		flush_rewrite_rules();

		$users = get_users();
		foreach ( $users as $user ) {
			if (user_can( $user, 'edit_mbdsc_stories')) {
				wp_insert_post(array('post_type'=>'mbdsc_author', 'post_title'=>$user->user_login, 'post_status'=>'publish', 'post_author'=>$user->ID));
			}
		}
	}
}
