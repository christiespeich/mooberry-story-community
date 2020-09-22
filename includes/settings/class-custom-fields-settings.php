<?php


class Mooberry_Story_Community_Custom_Fields_Settings {

	private static function get( $field, $default = '' ) {
		return Mooberry_Story_Community_Settings::get( 'mbdsc_custom_fields_options', $field, $default );
	}

}
