<?php


class Mooberry_Story_Community_Reader_Factory {

	/*public function create_author_cpt() {
		return new Mooberry_Story_Community_Author_CPT();
	}*/

	public function create_reader( $id = 0 ) {
		$user = get_user_by('id', $id );
		$roles = (array) $user->roles;
		if ( in_array(MOOBERRY_STORY_COMMUNITY_ROLE_AUTHOR, $roles ) || in_array('administrator', $roles)) {
			return new Mooberry_Story_Community_Author( $id );
		} else {
			return new Mooberry_Story_Community_Reader( $id );
		}
	}

}
