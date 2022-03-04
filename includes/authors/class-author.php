<?php


class Mooberry_Story_Community_Author extends Mooberry_Story_Community_Reader {

	protected $author_profile_picture;

	public function __construct( $user_id = 0) {

		parent::__construct($user_id, 'mbdsc_author');

		$this->author_profile_picture = get_user_meta( $user_id, 'mbdsc_author_profile_image', true );

			if ($this->author_profile_picture != '' ) {
				$this->avatar = $this->author_profile_picture;
			} else {
				$this->avatar = get_avatar( $user_id );
			}

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
