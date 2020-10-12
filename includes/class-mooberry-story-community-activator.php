<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.mooberrydreams.com
 * @since      1.0.0
 *
 * @package    Mooberry_Story_Community
 * @subpackage Mooberry_Story_Community/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Mooberry_Story_Community
 * @subpackage Mooberry_Story_Community/includes
 * @author     Mooberry Dreams <support@mooberrydreams.com>
 */
class Mooberry_Story_Community_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {


		remove_role( MOOBERRY_STORY_COMMUNITY_ROLE_ADMIN );
		$admin_role = add_role( MOOBERRY_STORY_COMMUNITY_ROLE_ADMIN, 'MB Story Community ' . __( 'Admin', 'mooberry-story-community' ) );
		$admin_role->add_cap( MOOBERRY_STORY_COMMUNITY_ADMIN_CAP );
		get_role( 'administrator' )->add_cap( MOOBERRY_STORY_COMMUNITY_ADMIN_CAP );

		/*remove_role( MOOBERRY_STORY_COMMUNITY_ROLE_MODERATOR );
		add_role( MOOBERRY_STORY_COMMUNITY_ROLE_MODERATOR, 'MB Story Community ' . __( 'Moderator', 'mooberry-story-community' ) );*/

		remove_role( MOOBERRY_STORY_COMMUNITY_ROLE_AUTHOR );
		add_role( MOOBERRY_STORY_COMMUNITY_ROLE_AUTHOR, 'MB Story Community ' . __( 'Author', 'mooberry-story-community' ) );

/*		remove_role( MOOBERRY_STORY_COMMUNITY_ROLE_MODERATED_AUTHOR );
		add_role( MOOBERRY_STORY_COMMUNITY_ROLE_MODERATED_AUTHOR, 'MB Story Community ' . __( 'Moderated Author', 'mooberry-story-community' ) );*/

		remove_role( MOOBERRY_STORY_COMMUNITY_ROLE_READER );
		add_role( MOOBERRY_STORY_COMMUNITY_ROLE_READER, 'MB Story Community ' . __( 'Reader', 'mooberry-story-community' ) );


		$has_been_installed = get_option('mbdsc_has_been_installed', false );
		if ( ! $has_been_installed ) {
			$custom_taxonomies = array(
				array(
					'display_toc'   => 'yes',
					'plural_name'   => __( 'Genres', 'mooberry-story-community' ),
					'singular_name' => __( 'Genre', 'mooberry-story-community' ),
					'slug'          => 'genre',
					'hierarchical'  => 'yes',
					'multiple'      => 'no',
					'required'      => 'no',
					'roles_can_add' => array(
						'administrator',
						MOOBERRY_STORY_COMMUNITY_ROLE_ADMIN,
					//	MOOBERRY_STORY_COMMUNITY_ROLE_MODERATOR,
					),
				),
			);
			Mooberry_Story_Community_Settings::update( 'mbdsc_taxonomy_fields_options', array( 'taxonomies' => $custom_taxonomies ) );
		}

		$story_cpt   = new Mooberry_Story_Community_Story_CPT();
		$chapter_cpt = new Mooberry_Story_Community_Chapter_CPT();
		$review_cpt = new Mooberry_Story_Community_Review_CPT();
		$cpts        = array( $story_cpt, $chapter_cpt );
		foreach ( $cpts as $cpt ) {
			$cpt->register();
			$cpt->set_up_roles();
		}
		flush_rewrite_rules();

		update_option('mbdsc_has_been_installed', true);


	}

}
