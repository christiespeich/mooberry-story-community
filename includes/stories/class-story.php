<?php


class Mooberry_Story_Community_Story extends Mooberry_Story_Community_Post_Object {


	protected $author;
	protected $summary;
	protected $chapters;
	protected $is_complete;
	protected $taxonomies;
	protected $cover;
	protected $cover_id;
	protected $word_count;
	protected $review_count;

	public function __construct( $id = 0 ) {

		parent::__construct($id, Mooberry_Story_Community_Custom_Fields_Settings::get_custom_story_fields() );


	}

	protected function init( ) {
		parent::init();


		$this->author  = null;
		$this->summary     = '';
		$this->chapters    = array();
		$this->is_complete = false;
		$this->taxonomies  = array();
		$this->cover       = '';
		$this->cover_id    = 0;
		$this->word_count = 0;

	}

	protected function load( $id, $custom_fields ) {
		parent::load( $id, $custom_fields);


		$this->author = new Mooberry_Story_Community_Author( $this->author_id );

		$this->summary =  get_post_meta( $this->id, 'mbdsc_story_summary', true );
		$this->cover    = get_post_meta( $this->id, 'mbdsc_story_cover', true );
		$this->cover_id = get_post_meta( $this->id, 'mbdsc_story_cover_id', true );
		$this->is_complete = get_post_meta( $this->id, 'mbdsc_story_complete', true ) === "on";

		$this->chapters = Mooberry_Story_Community_Chapter_Collection::get_chapters_by_story($this->id);
		foreach ( $this->chapters as $chapter ) {
			$this->word_count = $this->word_count + $chapter->word_count;
			$this->review_count = $this->review_count + $chapter->review_count;
		}



		$taxonomies = Mooberry_Story_Community_Custom_Taxonomies_Settings::get_taxonomies();
		foreach ( $taxonomies as $taxonomy ) {
			$tax = new StdClass();
			$tax->slug = $taxonomy->slug;
			$tax->name = $taxonomy->singular_name;
			$tax->taxonomy = $taxonomy->taxonomy;
			$terms = wp_get_post_terms($this->id, $taxonomy->taxonomy);
			if ( is_wp_error($terms )) {
				$tax->terms = array();
			} else {
				$tax->terms = $terms;
			}
			$this->taxonomies[ $taxonomy->slug ] = $tax;
		}



	}

	public function get_most_recent_chapter() {
		$updated_posts = get_posts( array(
			'orderby'        => 'post_modified',
			'order'          => 'DESC',
			'post_type'      => array( 'mbdsc_chapter' ),
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_key'       => 'mbdsc_chapter_story',
			'meta_value'     => $this->id,
		) );
		if ( count( $updated_posts ) > 0 ) {
			return new Mooberry_Story_Community_Chapter( $updated_posts[0]->ID );
		}

		return null;
	}

	public function get_most_recent_chapter_date() {
		$chapter = $this->get_most_recent_chapter();
		if ( $chapter ) {
			return $chapter->last_updated;
		}
		return '';
	}

	public function get_chapter_count( ) {
		if ( !is_array($this->chapters)) {
			return 0;
		}
		return count( $this->chapters);
	}

	public function get_next_chapter( $chapter_id ) {
		foreach ( $this->chapters as $key => $chapter ) {
			if ( $chapter->id == $chapter_id ) {
				$key++;
				break;
			}
		}
		if ( $key < count($this->chapters )) {
			return $this->chapters[ $key ];
		}
		return null;
	}

	public function get_prev_chapter( $chapter_id ) {
		foreach ( $this->chapters as $key => $chapter ) {
			if ( $chapter->id == $chapter_id ) {
				$key--;
				break;
			}
		}
		if ( $key > -1) {
			return $this->chapters[ $key ];
		}
		return null;
	}

	public function __get( $name ) {
		if ( property_exists(  $this, $name ) )  {
			return $this->$name;
		}

		return "";
	}
}
