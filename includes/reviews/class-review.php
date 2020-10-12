<?php


class Mooberry_Story_Community_Review extends Mooberry_Story_Community_Post_Object {


	protected $reviewer_email;
	protected $reviewer_name;
	protected $review_content;
	protected $chapter_id;
	protected $story;
	protected $timestamp;

	public function __construct( $id = 0 ) {

		parent::__construct($id, array() );


	}

	protected function init( ) {
		parent::init();


		$this->review_content = '';
		$this->reviewer_email = '';
		$this->reviewer_name = '';
		$this->chapter_id        = 0;
		$this->timestamp =0;
	}

	protected function load( $id, $custom_fields ) {
		parent::load( $id, $custom_fields );

		if ( $this->post ) {
			$this->reviewer_email = get_post_meta( $this->id, 'mbdsc_reviewer_email', true );
			$this->reviewer_name = get_post_meta( $this->id, 'mbdsc_reviewer_name', true );
			$this->review_content = get_post_meta( $this->id, 'mbdsc_review_content', true );
			$this->chapter_id           = get_post_meta( $this->id, 'mbdsc_review_chapter', true );
			$this->timestamp = $this->post->post_date;
			/*if ( $chapter_id != '' ) {
				$this->chapter = new Mooberry_Story_Community_Chapter( $chapter_id );
				if ( $this->chapter ) {
					$this->story = new Mooberry_Story_Community_Story( $this->chapter->story_id );

				}


			}*/
		}
	}



	/*protected function get_story() {
		if ( $this->chapter) {
			return $this->chapter->
		}
	}*/

	public function __get( $name ) {
		if ( method_exists( $this, 'get_' . $name ) ) {
			return call_user_func( array( $this, 'get_' . $name ) );
		}
		if ( property_exists( $this, $name ) ) {
			return $this->$name;
		}

		return "";
	}
}
