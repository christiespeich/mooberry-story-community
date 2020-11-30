<?php


class Mooberry_Story_Community_Main_Settings {

	private static function get( $field, $default = '' ) {
		return Mooberry_Story_Community_Settings::get( 'mbdsc_main_settings', $field, $default );
	}

	private static function set( $field, $value ) {
		Mooberry_Story_Community_Settings::set( 'mbdsc_main_settings', $field, $value );
	}
	public static function get_review_show_email() {
		return self::get('mbdsc_review_show_email', 'no' );
	}

	public static function get_page( $page_id ) {
		return self::get ($page_id, 0);
	}

	public static function set_page( $page_id, $value ) {
		self::set( $page_id, $value );
	}

	public static function get_account_settings_page() {
		return self::get_page('mbdsc_pages_account_settings');
	}

	public static function get_edit_story_page() {
		return self::get_page('mbdsc_pages_edit_story');
	}

	public static function get_edit_chapter_page() {
		return self::get_page('mbdsc_pages_edit_chapter');
	}
}
