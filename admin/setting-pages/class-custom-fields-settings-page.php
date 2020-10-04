<?php

/**
 * Admin Settings Page
 * This class is taken directly from the CMB2 example
 * and customized for MBDS
 *
 * @since 3.0
 */
class Mooberry_Story_Community_Custom_Fields_Page extends Mooberry_Story_Community_Tabbed_Settings_Page {


	/**
	 * Constructor
	 *
	 * @since 3.0
	 */
	public function __construct() {

		$this->tab_group = 'mbdsc_custom_fields';

		parent::__construct( 'mbdsc_custom_fields_metabox', 'mbdsc_custom_fields_options', $this->tab_group, 'Custom Fields' );


		$this->set_parent_slug( 'mbdsc_main_settings' );
		$this->set_title( __( 'Custom Fields', 'mooberry-story-community' ) );
		$this->set_menu_title( __( 'Custom Fields', 'mooberry-story-community' ) );

		$this->create_metabox();
		$this->add_fields();

		$this->add_tab( new Mooberry_Story_Community_Taxonomy_Fields_Tab( $this ) );

		$custom_fields = Mooberry_Story_Community_Custom_Fields_Settings::get_custom_fields();
			foreach ( $custom_fields as $field ) {
			if ( $field->has_options ) {
				$this->add_tab( new Mooberry_Story_Community_Custom_Field_Options_Tab( $this, $field->unique_id, $field->name));

			}
		}


	}


	function add_fields() {

		$mbdb_settings_metabox = $this->metabox;
		$mbdb_settings_metabox->add_field( array(
				'id'      => 'custom_fields',
				'type'    => 'group',
				'desc'    => __( 'Create your custom fields for stories here. If you would like to create a field to organize stories, such as genre, click on the Taxonomy Fields tab.', 'mooberry-story-community' ) ,
				'options' => array(
					'group_title'   => __( 'Field', 'mooberry-story-community' ) . ' {#}',
					// since version 1.1.4, {#} gets replaced by row number
					'add_button'    => __( 'Add New Field', 'mooberry-story-community' ),
					'remove_button' => __( 'Remove Field', 'mooberry-story-community' ),
					'sortable'      => true,
					// beta
				),
			)
		);

		$mbdb_settings_metabox->add_group_field( 'custom_fields', array(
				'name'       => __( 'Field', 'mooberry-story-community' ),
				'id'         => 'name',
				'type'       => 'text_medium',
				'attributes' => array(
					'required' => 'required',
				),
			)
		);

		$mbdb_settings_metabox->add_group_field( 'custom_fields', array(
				'name' => __( 'Description', 'mooberry-story-community' ),
				'id'   => 'description',
				'type' => 'text',
				'desc' => __( 'This text is an example of a description and how it will appear.', 'mooberry-story-community' ),
			)
		);

		$mbdb_settings_metabox->add_group_field( 'custom_fields', array(
				'name'    => __( 'Type', 'mooberry-story-community' ),
				'id'      => 'type',
				'type'    => 'select',
				'desc'    => __( 'For fields of type Drop Down, Radio, or Checkbox, you\'ll be able to set the options on a new tab after you have saved the fields.', 'mooberry-story-community' ),
				'options' => array(
					'text'       => 'Text Box',
					'multicheck' => 'Checkbox',
					'radio'      => 'Radio Button',
					'select'     => 'Drop Down',
					'checkbox'   => 'Yes/No Toggle',
				),

			)
		);
/*

		$mbdb_settings_metabox->add_group_field( 'custom_fields', array(
				'name' => __( 'Is this field for stories or chapters?', 'mooberry-story-community' ),
				'id'   => 'story_or_chapter',
				'type' => 'select',
				'options'   =>  array('story'=>__('Story', 'mooberry-story-community'),'chapter'=>__('Chapter', 'mooberry-story-community')),

			)
		);*/


		$mbdb_settings_metabox->add_group_field( 'custom_fields', array(
				'name' => __( 'Disable Field', 'mooberry-story-community' ),
				'id'   => 'disabled',
				'type' => 'checkbox',
				'desc' => __( 'Fields that are disabled are not shown when editing/adding a book nor when displaying the book page.  By disabling a field instead of deleting it, the information assigned to that field will not be erased.', 'mooberry-story-community' )
			)
		);

/*		$mbdb_settings_metabox->add_group_field( 'custom_fields', array(
				'name' => __( 'Hide Field', 'mooberry-story-community' ),
				'id'   => 'hidden',
				'type' => 'checkbox',
				'desc' => __( 'Fields that may contain spoiler information (such as whether the ending is "happy-ever-after" or not) can be hidden. The user will then have to click a link to display the information.', 'mooberry-story-community'
				)
			)
		);*/


		$mbdb_settings_metabox->add_group_field( 'custom_fields', array(
				'id'              => 'uniqueID',
				'type'            => 'text',
				'show_names'      => false,
				'sanitization_cb' => array( $this, 'get_uniqueID' ),
				'attributes'      => array(
					'type' => 'hidden',
				),
			)
		);


	}


	protected function set_pages() {
		$this->pages = array(
			'mbdsc_custom_fields_options' => array(
				'page_title' => __( 'Mooberry Story Custom Fields', 'mooberry-story-premium' ),
				'menu_title' => __( 'Custom Fields', 'mooberry-story-premium' ),
			),

		);


		$this->pages = apply_filters( 'mbdsc_settings_pages', $this->pages );


	}


	/**
	 * Register settings notices for display
	 *
	 * @param int   $object_id Option key
	 * @param array $updated   Array of updated fields
	 *
	 * @return void
	 * @since  0.1.0
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}

		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'mooberry-story-premium' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 *
	 * @param string $field Field to retrieve
	 *
	 * @return mixed          Field value or exception is thrown
	 * @since  0.1.0
	 */
	public function __get( $field ) {
		return parent::__get( $field );
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}

	function get_uniqueID( $value ) {

		usleep( 3 );
		if ( $value == '' ) {
			$value = uniqid();
		}

		return $value;

	}

} // end class






