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

		remove_role(MOOBERRY_STORY_COMMUNITY_ROLE_ADMIN);
		//remove_role(MOOBERRY_STORY_COMMUNITY_ROLE_MODERATOR);
		remove_role(MOOBERRY_STORY_COMMUNITY_ROLE_AUTHOR);
		//remove_role(MOOBERRY_STORY_COMMUNITY_ROLE_MODERATED_AUTHOR);
		remove_role(MOOBERRY_STORY_COMMUNITY_ROLE_READER);

	}

}
