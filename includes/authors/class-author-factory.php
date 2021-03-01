<?php


class Mooberry_Story_Community_Author_Factory {

	public function create_author_cpt() {
		return new Mooberry_Story_Community_Author_CPT();
	}

	public function create_author( $id = 0 ) {
		return new Mooberry_Story_Community_Author( $id );
	}

}
