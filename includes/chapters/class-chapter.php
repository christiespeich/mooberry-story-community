<?php


class Mooberry_Story_Community_Chapter extends Mooberry_Story_Community_Post_Object {

	protected $id;
	protected $post;
	protected $title;
	protected $body;
	protected $story_id;
	protected $order;
	protected $word_count;
	protected $reviews;
	protected $review_count;


	public function __construct( $id = 0) {

		parent::__construct($id, Mooberry_Story_Community_Custom_Fields_Settings::get_custom_chapter_fields() );



	}

	protected function init() {
		parent::init();
		$this->body = '';
		$this->story_id = 0;
		$this->order = 0;
		$this->word_count = 0;
		$this->reviews = array();
		$this->review_count = 0;
	}

	protected function load ( $id, $custom_fields ) {
		parent::load( $id, $custom_fields );
		if ( $this->post ) {
			$this->body = $this->post->post_content;
			$this->story_id = get_post_meta( $id, 'mbdsc_chapter_story', true );
			$this->order = get_post_meta( $id, '_mbdsc_chapter_order', true );
			$this->word_count = $this->count_words();
			$this->reviews = Mooberry_Story_Community_Review_Collection::get_reviews_by_chapter($this->id);
			$this->review_count = count($this->reviews);

		}
	}


	protected function count_words(  ) {
		$content          = $this->post->post_content;
		$decode_content   = html_entity_decode( $content );
		$filter_shortcode = do_shortcode( $decode_content );
		$strip_tags       = wp_strip_all_tags( $filter_shortcode, true );
		$count            = str_word_count( $strip_tags );

		return $count;
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
