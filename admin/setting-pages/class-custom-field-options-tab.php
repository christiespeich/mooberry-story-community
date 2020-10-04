<?php


class Mooberry_Story_Community_Custom_Field_Options_Tab extends Mooberry_Story_Community_Settings_Tab {

	protected $unique_id;
	protected $name;

	public function __construct( Mooberry_Story_Community_Tabbed_Settings_Page $setting_page, $unique_id, $name ) {
		parent::__construct( 'mbdsc_field_' . $unique_id . 'options_metabox', 'mbdsc_field_' . $unique_id . '_options', $name . ' Options', $setting_page );

		$this->unique_id = $unique_id;
		$this->name = $name;

		$this->create_metabox();
		$this->add_fields();


	}


	/**
	 * Add the options metabox to the array of metaboxes
	 * Choose which metabox based on $tab
	 *
	 * @since  3.0
	 */
	function add_fields() {


		$this->add_field( array(
				'id'      => 'custom_field_options_' . $this->unique_id,
				'type'    => 'group',

				'options' => array(
					'group_title'   => __( 'Option', 'mooberry-story-premium' ) . ' {#}',
					// since version 1.1.4, {#} gets replaced by row number
					'add_button'    => __( 'Add New Option', 'mooberry-story-premium' ),
					'remove_button' => __( 'Remove Option', 'mooberry-story-premium' ),
					'sortable'      => true,
					// beta
				),
			)
		);

	$this->add_group_field( 'custom_field_options_' . $this->unique_id, array(
						'name' => __('Option Value', 'mbm-book-shop'),
						'id'   => 'value',
						'type' => 'text_medium',
						'attributes' => array(
							'required' => 'required',
						),
					)
				);
				$this->add_group_field( 'custom_field_options_' . $this->unique_id, array(
						'id' => 'uniqueID',
						'type' => 'text',
						'show_names' => false,
						'sanitization_cb' => array($this, 'get_uniqueID'),
						'attributes' => array(
							'type' => 'hidden',
						),
					)
				);
	}

	function get_uniqueID( $value ) {

		usleep( 3 );
		if ( $value == '' ) {
			$value = uniqid(); // . '_' . sanitize_title($field['name']);
		}

		return $value;

	}



}
