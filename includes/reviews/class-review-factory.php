<?php


class Mooberry_Story_Community_Review_Factory {

	public function create_review_cpt() {
		return new Mooberry_Story_Community_Review_CPT();
	}

	public function create_review( $id = 0 ) {
		return new Mooberry_Story_Community_Review( $id );
	}

	public function create_review_collection() {
		return new Mooberry_Story_Community_Review_Collection();
	}

}
