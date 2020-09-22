<?php


class Mooberry_Story_Community_Custom_Taxonomies_Settings {

	private static function get( $field, $default = '' ) {
		return Mooberry_Story_Community_Settings::get( 'mbdsc_taxonomy_fields_options', $field, $default );
	}

	public static function get_taxonomies() {
		return self::get('taxonomies', array());
	}

}
