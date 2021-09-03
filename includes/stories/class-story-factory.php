<?php


class Mooberry_Story_Community_Story_Factory {

	public function create_story_cpt() {
		return new Mooberry_Story_Community_Story_CPT();
	}

	public function create_story( $id = 0 ) {
		return new Mooberry_Story_Community_Story( $id );
	}

	public function create_story_collection() {
		return new Mooberry_Story_Community_Story_Collection();
	}
}
