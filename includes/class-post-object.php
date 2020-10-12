<?php


class Mooberry_Story_Community_Post_Object {


	protected $id;
	protected $post;
	protected $title;
	protected $author_id;
	protected $custom_fields;
	protected $link;
	protected $slug;


		public function __construct( $id = 0, $custom_fields = array() ) {

		$this->init();

		if ( $id > 0 ) {
			$this->load( $id, $custom_fields );
		}


	}

	protected function init() {
		$this->id          = 0;
		$this->post        = null;
		$this->author_id = 0;
		$this->title = '';
		$this->link = '';
		$this->slug = '';
		$this->custom_fields = array();
	}

	protected function load( $id, $custom_fields ) {
		$this->id   = $id;
		$this->post = get_post( $id );
		if ( $this->post ) {
			$this->title   = $this->post->post_title;
			$this->link = get_permalink($id);
			$this->author_id = $this->post->post_author;
			$this->slug = $this->post->post_name;
		}


		foreach ( $custom_fields as $custom_field ) {
			$field = new StdClass();
			$field->unique_id = $custom_field->unique_id;
			$field->name = $custom_field->name;
			$field->value = get_post_meta( $this->id, 'mbdsc_custom_field_' . $custom_field->unique_id, true );
			if ( $custom_field->has_options ) {
				$field->value = $custom_field->get_option_value( $field->value );
			}
			$this->custom_fields[$custom_field->unique_id] = $field;
		}


	}


	public function __get( $name ) {
			if ( method_exists( $this, 'get_' . $name ) ) {
			return call_user_func( array( $this, 'get_' . $name ) );
		}
		if ( property_exists(  $this, $name ) )  {
			return $this->$name;
		}

		return "";
	}



}
