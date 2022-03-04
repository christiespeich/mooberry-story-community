<?php


class Mooberry_Story_Community_Reader extends Mooberry_Story_Community_Post_Object {

	protected $user_id;
	protected $user;
	protected $avatar;
	protected $bio;

	public function __construct( $user_id = 0, $post_type = 'mbdsc_reader' ) {
		$this->post_type = $post_type;
		$reader_id       = 0;
		$wp_user         = get_user_by( 'ID', $user_id );
		if ( $wp_user ) {
			$reader_posts = get_posts( array(
				'post_type'   => $this->post_type,
				'title'       => $wp_user->user_login,
				'fields'      => 'ids',
				'post_status' => 'publish'
			) );
			if ( is_array( $reader_posts ) && count( $reader_posts ) > 0 ) {
				$reader_id = $reader_posts[0];
			}
		}
		$this->user_id = intval( $user_id );
		parent::__construct( $reader_id, array() );


	}

	protected function init() {
		$this->user   = new WP_User(0);
		$this->link   = '';
		$this->avatar = '';
		$this->bio    = '';
	}

	protected function load( $reader_id, $custom_fields ) {
		parent::load( $reader_id, $custom_fields );
		$user = get_user_by( 'id', $this->user_id );
		if ( $user ) {
			$this->user    = $user;
				$this->avatar = get_avatar( $reader_id );

			$this->bio = $user->description;
		}

	}

	protected function get_display_name() {
		if ( $this->user ) {
			return $this->user->display_name;
		}
		return '';
	}

	protected function get_email() {
		if ( $this->user ) {
			return $this->user->user_email;
		}
		return '';
	}

	public function is_favorite_author( $author_id ) {
		$fave_authors = get_user_meta( $this->user_id, '_mbdsc_fave_author' );

		return is_array( $fave_authors ) && in_array( $author_id, $fave_authors );
	}

	public function is_favorite_story( $story_id ) {
		$fave_stories = get_user_meta( $this->user_id, '_mbdsc_fave_story' );

		return is_array( $fave_stories ) && in_array( $story_id, $fave_stories );
	}

	public function add_favorite_author( $author_id ) {
		$this->remove_favorite_author( $author_id );
		add_user_meta( $this->user_id, '_mbdsc_fave_author', $author_id );
	}

	public function add_favorite_story( $story_id ) {
		$this->remove_favorite_story( $story_id );
		add_user_meta( $this->user_id, '_mbdsc_fave_story', $story_id );
	}

	public function remove_favorite_author( $author_id ) {
		delete_user_meta( $this->user_id, '_mbdsc_fave_author', $author_id );
	}

	public function remove_favorite_story( $story_id ) {
		delete_user_meta( $this->user_id, '_mbdsc_fave_story', $story_id );
	}

	public function toggle_favorite_author( $author_id ) {
		if ( $this->is_favorite_author( $author_id ) ) {
			$this->remove_favorite_author( $author_id );
		} else {
			$this->add_favorite_author( $author_id );
		}
	}
	public function toggle_favorite_story( $story_id ) {
		if ( $this->is_favorite_story( $story_id ) ) {
			$this->remove_favorite_story( $story_id );
		} else {
			$this->add_favorite_story( $story_id );
		}
	}

	public function get_favorite_authors() {
		$fave_authors = get_user_meta( $this->user_id, '_mbdsc_fave_author' );
		global $mbdsc_author_factory;
		$authors = array();
		foreach ( $fave_authors as $fave_author ) {
			$authors[] = $mbdsc_author_factory->create_author( $fave_author );
		}

		return $authors;
	}

	public function get_favorite_stories() {
		$fave_stories = get_user_meta( $this->user_id, '_mbdsc_fave_story' );
		global $mbdsc_story_factory;
		$stories = array();
		foreach ( $fave_stories as $fave_story ) {
			$stories[] = $mbdsc_story_factory->create_story( $fave_story );
		}

		return $stories;
	}

	/*protected function get_link() {

		return get_reader_posts_url( $this->id );

	}*/


	public function __get( $name ) {

		if ( method_exists( $this, 'get_' . $name ) ) {
			return call_user_func( array( $this, 'get_' . $name ) );
		}
		if ( property_exists( $this, $name ) ) {
			return $this->{$name};
		}

		return '';
	}
}
