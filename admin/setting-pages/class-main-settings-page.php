<?php


class Mooberry_Story_Community_Main_Settings_Page  extends Mooberry_Story_Community_Settings_Page {


	public function __construct() { //Mooberry_Directory_Settings_Page $main_settings_tab ) {
		parent::__construct( 'mbdsc_main_settings_page_metabox', 'mbdsc_main_settings');


		$this->set_menu_title( __( 'Story Community Settings', 'mooberry-story-community' ) );
		$this->set_title( __( 'Settings', 'mooberry-story-community' ) );
		$this->create_metabox();

		$this->add_fields_on_first_tab();
		$this->add_tabs();

	}

	protected function add_tabs() {
	/*	$photo_tab   = new Mooberry_Directory_Photo_Settings_Page( $this );
		$email_tab   = new Mooberry_Directory_Email_Settings_Page( $this );
		$renewal_tab = new Mooberry_Directory_Renewal_Settings_Page( $this );
		$maps_tab   = new Mooberry_Directory_Maps_Settings_Page( $this );
		$taxonomies  = new Mooberry_Directory_Taxonomy_Settings_Page( $this );
		$form_fields = new Mooberry_Directory_Listing_Fields_Page( $this );
		$tour_settings = new Mooberry_Directory_Tours_Settings_Page( $this );
		//$events_settings = new Mooberry_Directory_Events_Settings_Page( $this );
		//$event_form_fields = new Mooberry_Directory_Events_Fields_Page( $this );
		$search_tab = new Mooberry_Directory_Search_Settings_Page( $this );
		$form_fields = new Mooberry_Directory_Listing_Sorting_Page( 'mbdsc_location_listing_sorting_options_page', 'mbdsc_location_listing_sorting', 'Location Page Listing Sorting', $this );
		$form_fields = new Mooberry_Directory_Listing_Sorting_Page( 'mbdsc_search_listing_sorting_options_page', 'mbdsc_search_listing_sorting', 'Search Page Listing Sorting', $this );*/
	}

	protected function add_fields_on_first_tab() {

	}
}
