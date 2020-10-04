<?php


class Mooberry_Story_Community_Custom_Fields_Settings {


	private static function get( $field, $default = '' ) {
		return Mooberry_Story_Community_Settings::get( 'mbdsc_custom_fields_options', $field, $default );
	}

	public static function get_custom_fields() {
		$fields = self::get('custom_fields', array());
		$custom_fields = array();
		if ( is_array( $fields)) {
			foreach ( $fields as $field ) {
				$custom_fields[] = new Mooberry_Story_Community_Custom_Field($field);
			}
			}
		return $custom_fields;
	}

	public static function get_custom_story_fields() {
		//return self::get_filtered_fields('story_or_chapter', 'story');
		return self::get_custom_fields();
	}

	public static function get_custom_chapter_fields() {
		return self::get_filtered_fields('story_or_chapter', 'chapter');
	}

	protected static function get_filtered_fields( $setting, $value ) {
		$all_fields = self::get( 'custom_fields', array() );
		$filtered   = array();
		if ( is_array($all_fields)) {
			foreach ( $all_fields as $field ) {
				if ( isset( $field[ $setting ] ) && $field[ $setting ] == $value ) {
					$filtered[] = new Mooberry_Story_Community_Custom_Field( $field );
				}
			}
		}
		return $filtered;
	}

	public static function get_custom_field_options( $field_id ) {
		$settings =  Mooberry_Story_Community_Settings::get('mbdsc_field_' . $field_id . '_options', 'custom_field_options_' . $field_id, array());
		$options = array();
		foreach ( $settings as $setting ) {
			$options[] = new Mooberry_Story_Community_Custom_Field_Option($setting, $field_id );
		}
		return $options;

	}


}
