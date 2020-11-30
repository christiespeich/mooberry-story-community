<?php


class Mooberry_Story_Community_Main_Settings_Page extends Mooberry_Story_Community_Settings_Page {


	public function __construct() { //Mooberry_Directory_Settings_Page $main_settings_tab ) {
		parent::__construct( 'mbdsc_main_settings_page_metabox', 'mbdsc_main_settings' );


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

		ob_start();
		include MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . '/admin/partials/create-pages-button.php';
		$button = ob_get_clean();

		$this->add_field( array(
				'id'    => 'mbdsc_pages_title',
				'type'  => 'title',
				'name'  => __( 'PAGES', 'mooberry-story-community' ),
				'after' => $button,
			)
		);

		$wp_pages    = get_pages();
		$pages_array = array( '0' => '' );
		foreach ( $wp_pages as $page ) {
			$pages_array[ $page->ID ] = $page->post_title;
		}

		$pages = array(
			array(
				'id'        => 'account_settings',
				'title'     => __( 'Users\'s Account Settings', 'mooberry-story-community' ),
				'shortcode' => MBDSC_ACCOUNT_PAGE_SHORTCODE,
			),
			array(
				'id'        => 'edit_story',
				'title'     => __( 'Edit Story Page', 'mooberry-story-community' ),
				'shortcode' => MBDSC_EDIT_STORY_PAGE_SHORTCODE,
			),
			array(
				'id'        => 'edit_chapter',
				'title'     => __( 'Edit Chapter Page', 'mooberry-story-community' ),
				'shortcode' => MBDSC_EDIT_CHAPTER_PAGE_SHORTCODE,
			),
		);
		foreach ( $pages as $page ) {
			$this->add_field( array(
				'id'          => "mbdsc_pages_{$page['id']}",
				'name'        => $page['title'],
				'type'        => 'select',
				'description' => __( 'This page will need the [' . $page['shortcode'] . '] shortcode', 'mooberry-story-community' ),
				'options'     => $pages_array,
			) );
		}

		$this->add_field( array(
				'id'   => 'mbdsc_review_settings_title',
				'type' => 'title',

				'name' => __( 'REVIEWS', 'mooberry-story-community' ),
			)
		);

		$this->add_field( array(
			'id'      => 'mbdsc_review_show_email',
			'name'    => __( 'Display Reviewer\'s Email Address With Review?', 'mooberry-story-community' ),
			'type'    => 'select',
			'options' => array(
				''    => '',
				'yes' => 'Yes',
				'no'  => 'No',
			),
		) );


	}
}
