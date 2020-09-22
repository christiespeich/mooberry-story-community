<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/20/2018
 * Time: 7:58 PM
 */

class Mooberry_Story_Community_Settings {

	protected static $options;

	public static function get( $setting_page, $field, $default = '' ) {
		if ( ! is_array( self::$options ) || ! array_key_exists( $setting_page, self::$options ) ) {
			self::load( $setting_page );
		}
		if ( array_key_exists( $setting_page, self::$options ) ) {
			if ( array_key_exists( $field, self::$options[ $setting_page ] ) ) {
				return self::$options[ $setting_page ][ $field ];
			}
		}
		return $default;
	}

	public static function set( $setting_page, $field, $value ) {
		self::load( $setting_page );
		self::$options[ $setting_page ][ $field ] = $value;
		self::update( $setting_page, self::$options[ $setting_page ] );
	}

	private static function load( $setting_page ) {
		self::$options[ $setting_page ] = get_option( $setting_page, array() );
	}

	public static function update( $key, $value ) {
		update_option( $key, $value );
		self::load( $key );
	}





}
