<?php


class Mooberry_Story_Community_Main_Settings {

	private static function get( $field, $default = '' ) {
		return Mooberry_Story_Community_Settings::get( 'mbdsc_main_settings', $field, $default );
	}

}
