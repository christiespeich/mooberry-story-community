<?php

class MBDSP_Plugin_Dependency {

	private $required_version;
	private $plugin_name;
	private $plugin_file;


	public function __construct( $version, $plugin, $file ) {
		$this->required_version = $version;
		$this->plugin_name = $plugin;
		$this->plugin_file =  $file;
	}

	 public function check_dependencies() {
		if( $this->missing_dependencies() ) {
			if ( current_user_can( 'activate_plugins' ) ) {
			  add_action( 'admin_init', array( $this, 'deactivate_add_on' ) );
			  add_action( 'admin_notices', array( $this, 'admin_notice' ) );
			}
			return false;
		}
		return true;
	}

	private function missing_dependencies( ) {

		$mbdb_version = get_option('mbds_version');
		return (!function_exists( 'mbds_activate' ) ||
					$mbdb_version == '' ||
					version_compare($mbdb_version, $this->required_version , '<') );
	}

	function admin_notice() {

			echo '<div class="updated"><p><strong>Mooberry Story (version ' . $this->required_version . ' or above)</strong> is required to use '  . $this->plugin_name . '. The plug-in has been <strong>deactivated</strong>. Please install/activate Mooberry Story to use ' . $this->plugin_name . '</p></div>';
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		}

	// deactivates the plugin
	function deactivate_add_on() {
		deactivate_plugins( plugin_basename( $this->plugin_file ) );
	}


}
