<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://www.mooberrydreams.com
 * @since      1.0.0
 *
 * @package    Mooberry_Story_Community
 * @subpackage Mooberry_Story_Community/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Mooberry_Story_Community
 * @subpackage Mooberry_Story_Community/includes
 * @author     Mooberry Dreams <support@mooberrydreams.com>
 */
class Mooberry_Story_Community_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

/*
		$story_cpt   = new Mooberry_Story_Community_Story_CPT();
		$chapter_cpt = new Mooberry_Story_Community_Chapter_CPT();
		$review_cpt  = new Mooberry_Story_Community_Review_CPT();
		$author_cpt  = new Mooberry_Story_Community_Author_CPT();
		$cpts        = array( $story_cpt, $chapter_cpt, $review_cpt, $author_cpt );
		foreach ( $cpts as $cpt ) {
			$cpt->remove_role_caps();
		}
		remove_role( MOOBERRY_STORY_COMMUNITY_ROLE_ADMIN );
		//remove_role(MOOBERRY_STORY_COMMUNITY_ROLE_MODERATOR);
		remove_role( MOOBERRY_STORY_COMMUNITY_ROLE_AUTHOR );
		//remove_role(MOOBERRY_STORY_COMMUNITY_ROLE_MODERATED_AUTHOR);
		remove_role( MOOBERRY_STORY_COMMUNITY_ROLE_READER );*/
	}

}
