<?php


class Mooberry_Story_Community_Chapter_Factory {

	public function create_chapter_cpt() {
		return new Mooberry_Story_Community_Chapter_CPT();
	}

	public function create_chapter( $id = 0 ) {
		return new Mooberry_Story_Community_Chapter( $id );
	}

	public function create_chapter_collection() {
		return new Mooberry_Story_Community_Chapter_Collection();
	}
}
