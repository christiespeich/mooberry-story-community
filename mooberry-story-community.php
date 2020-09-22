<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.mooberrydreams.com
 * @since             1.0.0
 * @package           Mooberry_Story_Community
 *
 * @wordpress-plugin
 * Plugin Name:       Mooberry Story Community
 * Plugin URI:        http://www.mooberrydreams.com/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Mooberry Dreams
 * Author URI:        http://www.mooberrydreams.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mooberry-story-community
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
if ( !defined('MOOBERRY_STORY_COMMUNITY_VERSION')) {
	define( 'MOOBERRY_STORY_COMMUNITY_VERSION', '1.0.0' );
}

if ( !defined('MOOBERRY_STORY_COMMUNITY_PLUGIN_VERSION_KEY')) {
	define( 'MOOBERRY_STORY_COMMUNITY_PLUGIN_VERSION_KEY', 'mbdsc_version' );
}

// Plugin Folder URL
if ( ! defined( 'MOOBERRY_STORY_COMMUNITY_PLUGIN_URL' ) ) {
	define( 'MOOBERRY_STORY_COMMUNITY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Plugin Root File
if ( ! defined( 'MOOBERRY_STORY_COMMUNITY_PLUGIN_FILE' ) ) {
	define( 'MOOBERRY_STORY_COMMUNITY_PLUGIN_FILE', __FILE__ );
}

if ( !defined('MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR')) {
	define( 'MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( !defined('MOOBERRY_STORY_COMMUNITY_ROLE_ADMIN')) {
	define( 'MOOBERRY_STORY_COMMUNITY_ROLE_ADMIN', 'mbdsc_admin' );
}

if ( !defined('MOOBERRY_STORY_COMMUNITY_ROLE_MODERATOR')) {
	define( 'MOOBERRY_STORY_COMMUNITY_ROLE_MODERATOR', 'mbdsc_moderator' );
}

if ( !defined('MOOBERRY_STORY_COMMUNITY_ROLE_AUTHOR')) {
	define( 'MOOBERRY_STORY_COMMUNITY_ROLE_AUTHOR', 'mbdsc_author' );
}

if ( !defined('MOOBERRY_STORY_COMMUNITY_ROLE_MODERATED_AUTHOR')) {
	define( 'MOOBERRY_STORY_COMMUNITY_ROLE_MODERATED_AUTHOR', 'mbdsc_moderated_author' );
}

if ( !defined('MOOBERRY_STORY_COMMUNITY_ROLE_READER')) {
	define( 'MOOBERRY_STORY_COMMUNITY_ROLE_READER', 'mbdsc_reader' );
}

if ( !defined('MOOBERRY_STORY_COMMUNITY_ADMIN_CAP')) {
	define( 'MOOBERRY_STORY_COMMUNITY_ADMIN_CAP', 'mbdsc_manage_options' );
}



/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mooberry-story-community-activator.php
 */
function activate_mooberry_story_community() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mooberry-story-community-activator.php';
	Mooberry_Story_Community_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mooberry-story-community-deactivator.php
 */
function deactivate_mooberry_story_community() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mooberry-story-community-deactivator.php';
	Mooberry_Story_Community_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mooberry_story_community' );
register_deactivation_hook( __FILE__, 'deactivate_mooberry_story_community' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mooberry-story-community.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mooberry_story_community() {

	$plugin = new Mooberry_Story_Community();
}

run_mooberry_story_community();
