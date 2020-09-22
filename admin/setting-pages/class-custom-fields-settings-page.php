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

parent::__construct( 'mbdsc_custom_fields_metabox', 'mbdsc_custom_fields_options', $this->tab_group, 'Custom Fields' );



		$this->set_parent_slug( 'mbdsc_main_settings' );
		$this->set_title( __( 'Custom Fields', 'mooberry-story-community' ) );
		$this->set_menu_title( __( 'Custom Fields', 'mooberry-story-community' ) );

$this->create_metabox();
$this->add_fields();

		$this->add_tab( new Mooberry_Story_Community_Taxonomy_Fields_Tab( $this) );

		add_action( 'update_option_mbds_options', array( $this, 'options_updated' ), 10, 2 );
		//add_filter( 'mbds_settings_metabox', array( $this, 'set_up_metabox' ) );

	}


	function add_fields(  ) {




		$this->add_field( array(
				'id'      => 'custom_fields',
				'type'    => 'group',

				'options' => array(
					'group_title'   => __( 'Taxonomy Field', 'mooberry-story-premium' ) . ' {#}',
					// since version 1.1.4, {#} gets replaced by row number
					'add_button'    => __( 'Add New Taxonomy Field', 'mooberry-story-premium' ),
					'remove_button' => __( 'Remove Taxonomy Field', 'mooberry-story-premium' ),
					'sortable'      => false,
					// beta
				),
			)
		);



		$this->add_group_field( 'custom_fields', array(
				'name'       => __( 'Display this field on the Table of Contents Page?', 'mooberry-story-premium' ),
				'id'         => 'display_toc',
				'type'       => 'select',
				'options'    => array(
					'yes' => __( 'Yes', 'mooberry-story-premium' ),
					'no'  => __( 'No', 'mooberry-story-premium' ),
				),
				'attributes' => array(
					'required' => 'required',
				),
			)
		);

		global $wp_roles;
		$user_roles = $wp_roles->get_names();
		if ( ! is_array( $user_roles ) ) {
			$user_roles = array( $user_roles );
		}

		$this->add_group_field( 'custom_fields', array(
				'name'       => __( 'Allow these user roles to add new items to this taxonomy', 'mooberry-story-premium' ),
				'id'         => 'roles_can_add',
				'type'       => 'multicheck',
				'options'    => $user_roles,
				'attributes' => array(
					'required' => 'required',
				),
			)
		);


		//return apply_filters( 'mbds_settings_core_metabox', $mbds_settings_metabox, $this->page, $this->tab );
		//return $mbds_settings_metabox;

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
		return parent::__get($field);
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}

	/**
	 *
	 *  Register all taxonomies
	 *  Flush rewrite rules
	 *  This function runs if ANY of the fields were updated.
	 *
	 *
	 * @param  [string] $old_value
	 * @param  [string] $new_value
	 *
	 * @access public
	 * @since  3.0
	 */
	public function options_updated( $old_value, $new_value ) {


		if ( array_key_exists( 'taxonomies', $new_value ) ) {
			MBDSC()->register_taxonomies( $new_value['taxonomies'] );
			$single = isset( $new_value['singular_name'] ) ? sanitize_text_field( $new_value['singular_name'] ) : '';
			$tax    = 'mbdb_' . sanitize_title( $single );

			// if any don't have slugs set, do it now
			if ( in_array( $single, mbds_wp_reserved_terms() ) ) {
				$single = 'book-' . $single;
			}

		}
		flush_rewrite_rules();
		global $wp_rewrite;
		$wp_rewrite->flush_rules();


	}

} // end class






