<?php


class Mooberry_Story_Community_Factory_Generator {

	static public function create_story_factory() {
		return apply_filters( 'mbdsc_story_factory', new Mooberry_Story_Community_Story_Factory());
	}

	static public function create_reader_factory() {
		return apply_filters( 'mbdsc_reader_factory', new Mooberry_Story_Community_Reader_Factory() );
	}


}
