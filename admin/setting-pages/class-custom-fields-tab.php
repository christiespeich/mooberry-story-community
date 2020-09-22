<?php


class Mooberry_Story_Community_Custom_Fields_Tab extends Mooberry_Story_Community_Settings_Tab {

	public function __construct( Mooberry_Story_Community_Tabbed_Settings_Page $setting_page ) {
		parent::__construct( 'mbdsc_custom_fields_metabox', 'mbdsc_custom_fields_options', 'Custom Fields', $setting_page );

		$this->add_fields();
	}



	/**
	 * Add the options metabox to the array of metaboxes
	 * Choose which metabox based on $tab
	 *
	 * @since  3.0
	 */


}
