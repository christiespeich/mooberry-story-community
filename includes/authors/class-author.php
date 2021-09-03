<?php


class Mooberry_Story_Community_Author extends Mooberry_Story_Community_Reader {

	protected $user_id;
	protected $user;
	protected $avatar;
	protected $bio;

	public function __construct( $user_id = 0) {

		parent::__construct($user_id, 'mbdsc_author');



	}


	public function __get( $name ) {

		if ( method_exists($this, 'get_' . $name ) ) {
			return call_user_func(array($this, 'get_' . $name));
		}
		if ( property_exists($this, $name)) {
			return $this->{$name};
		}
		return '';
	}
}
