<?php


class Mooberry_Story_Community_Custom_Field_Option {

	protected $unique_id;
	protected $value;
	protected $field_unique_id;

	public function __construct( $option, $field ) {
		$this->unique_id       = isset( $option['uniqueID'] ) ? $option['uniqueID'] : '';
		$this->value           = isset( $option['value'] ) ? $option['value'] : '';
		$this->field_unique_id = $field;

	}

	public function __get( $name ) {
		if ( property_exists( $this, $name ) ) {
			return $this->$name;
		}

		return '';
	}
}
