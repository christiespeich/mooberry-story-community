<?php


class Mooberry_Story_Community_Custom_Field {

	protected $unique_id;
	protected $name;
	protected $description;
	protected $type;
	protected $is_story_field;
	protected $is_chapter_field;
	protected $is_disabled;
	protected $is_hidden;
	protected $has_options;
	protected $options;

	public function __construct( $options ) {

		$this->unique_id        = isset( $options['uniqueID'] ) ? $options['uniqueID'] : '';
		$this->name             = isset( $options['name'] ) ? $options['name'] : '';
		$this->description      = isset( $options['description'] ) ? $options['description'] : '';
		$this->type             = isset( $options['type'] ) ? $options['type'] : '';
		$this->is_story_field   = isset( $options['story_or_chapter'] ) && $options['story_or_chapter'] == 'story';
		$this->is_chapter_field = isset( $options['story_or_chapter'] ) && $options['story_or_chapter'] == 'chapter';
		$this->is_disabled      = isset( $options['disabled'] ) && $options['disabled'];
		$this->is_hidden        = isset( $options['hidden'] ) && $options['hidden'];
		$this->has_options      = in_array( $this->type, array( 'select', 'radio', 'multicheck' ) );

		$this->options = array();
		if ( $this->has_options ) {
			$options = Mooberry_Story_Community_Custom_Fields_Settings::get_custom_field_options( $this->unique_id );
			foreach ( $options as $option ) {
				$this->options[ $option->unique_id ] = $option;
			}
		}

	}

	public function get_option_value( $option_key ) {
		if ( $option_key == '' ) {
			return '';
		}
		return isset( $this->options[ $option_key ] ) ? $this->options[ $option_key ]->value : '';
	}
	public function get_options_list( $include_blank = true ) {
		$options = array();
		if ( $include_blank && $this->type == 'select') {
			$options[''] = '';
		}
		foreach ( $this->options as $option ) {
			$options[ $option->unique_id ] = $option->value;

		}

		return $options;
	}

	public function __get( $name ) {
		if ( property_exists( $this, $name ) ) {
			return $this->$name;
		}

		return '';
	}
}
