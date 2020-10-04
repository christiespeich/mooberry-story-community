<?php


class Mooberry_Story_Community_Custom_Taxonomies_Settings {

	private static function get( $field, $default = '' ) {
		return Mooberry_Story_Community_Settings::get( 'mbdsc_taxonomy_fields_options', $field, $default );
	}

	public static function get_taxonomies() {
		$taxonomy_options = self::get('taxonomies', array());
		$taxonomies = array();
		if ( is_array( $taxonomy_options ) ) {
			foreach ( $taxonomy_options as $taxonomy ) {
				$taxonomies[] = new Mooberry_Story_Community_Custom_Taxonomy($taxonomy);
			}
		}
		return $taxonomies;
	}

}
