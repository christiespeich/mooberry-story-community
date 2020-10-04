<?php


class Mooberry_Story_Community_Chapter extends Mooberry_Story_Post_Object {

	protected $id;
	protected $post;
	protected $title;
	protected $body;
	protected $story_id;
	protected $order;

	public function __construct( $id = 0) {

		parent::__construct($id, Mooberry_Story_Community_Custom_Fields_Settings::get_custom_chapter_fields() );



	}

	protected function init() {
		parent::init();
		$this->body = '';
		$this->story_id = 0;
		$this->order = 0;
	}

	protected function load ( $id, $custom_fields ) {
		parent::load( $id, $custom_fields );
		if ( $this->post ) {
			$this->body = $this->post->post_content;
			$this->story_id = get_post_meta( $id, 'mbdsc_chapter_story', true );
			$this->order = get_post_meta( $id, '_mbdsc_chapter_order', true );
		}
	}

	protected function get_link() {
		return get_permalink( $this->post );
	}

	public function __get( $name ) {
		if ( method_exists( $this, 'get_' . $name ) ) {
			return call_user_func( array( $this, 'get_' . $name ) );
		}
		if ( property_exists( $this, $name ) ) {
			return $this->$name;
		}
	}


}
