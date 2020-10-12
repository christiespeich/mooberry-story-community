<?php


class Mooberry_Story_Community_Author extends Mooberry_Story_Community_Post_Object {

	protected $user_id;
	protected $user;
	protected $avatar;
	protected $bio;

	public function __construct( $user_id = 0) {

		$author_id = 0;
		$wp_user = get_user_by( 'ID', $user_id );
		$author_posts = get_posts( array( 'post_type'=>'mbdsc_author', 'title'=>$wp_user->user_login, 'fields'=>'ids', 'post_status' => 'publish') );
		if (  is_array($author_posts) ) {
			$author_id = $author_posts[0];
		}
		$this->user_id = intval($user_id);
		parent::__construct($author_id, array() );



	}

	protected function init() {
		$this->user = null;
		$this->link = '';
		$this->avatar = '';
		$this->bio = '';
	}

	protected function load( $author_id, $custom_fields ) {
		parent::load( $author_id, $custom_fields );
		$user = get_user_by('id', $this->user_id);
		if ( $user ) {
			$this->user = $user;
			$profile_image = $this->user->mbdsc_author_profile_image;
			if ( $profile_image != '' ) {
				$this->avatar = $profile_image;
			} else {
				$this->avatar = get_avatar( $author_id );
			}
			$this->bio = $user->description;
		}

	}

	protected function get_display_name() {
		if ( $this->user ) {
			return $this->user->display_name;
		}
	}

	/*protected function get_link() {

		return get_author_posts_url( $this->id );

	}*/



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
