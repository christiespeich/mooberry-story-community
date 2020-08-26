<?php
/**
 * Admin Settings Page
 * This class is taken directly from the CMB2 example
 * and customized for MBDS
 *
 * @since 3.0
 */
class Mooberry_Story_Core_Settings extends Mooberry_Story_Settings {


	/**
	 * Constructor
	 *
	 * @since 3.0
	 */
	public function __construct() {
		parent::__construct();

		$this->metabox_id = 'mbds_custom_fields_metabox';
		$this->key        = 'mbds_custom_fields_options';
        $this->title = __( 'Mooberry Story Custom Fields', 'mooberry-story' );

		add_action( 'update_option_mbds_options', array( $this, 'options_updated' ), 10, 2 );
		add_filter( 'mbds_custom_fields_metabox', array( $this, 'set_up_metabox' ) );

	}



	protected function set_pages() {
		$this->pages = array(
			'mbds_custom_fields_options'            => array(
				'page_title' => __( 'Mooberry Story Custom Fields', 'mooberry-story' ),
				'menu_title' => __( 'Custom Fields', 'mooberry-story' )
			),

		);


		$this->pages = apply_filters( 'mbds_settings_pages', $this->pages );


	}


	/**
	 * Add the options metabox to the array of metaboxes
	 * Choose which metabox based on $tab
	 *
	 * @since  3.0
	 */
	function set_up_metabox( $mbds_settings_metabox ) {



		$mbds_settings_metabox->add_field(array(
				'id'	=> 'author_page_title',
				'name'	=>	__('TAXONOMY FIELDS', 'mooberry-story-premium'),
				'type'	=>	'title',
			)
		);

		$mbds_settings_metabox->add_field(array(
				'id'          => 'taxonomies',
				'type'        => 'group',
				'desc'			=>	__('Taxonomies organize books. Adding a Taxonomy Field will create a new field that works like Genres, Series, Tags, etc.', 'mooberry-book-manager'),
				'options'     => array(
					'group_title'   => __('Taxonomy Field', 'mooberry-book-manager') . ' {#}',  // since version 1.1.4, {#} gets replaced by row number
					'add_button'    =>  __('Add New Taxonomy Field', 'mooberry-book-manager'),
					'remove_button' =>  __('Remove Taxonomy Field', 'mooberry-book-manager') ,
					'sortable'      => false, // beta
				),
			)
		);

		$mbds_settings_metabox->add_group_field( 'taxonomies', array(
				'name' => __('Singular Name', 'mooberry-book-manager'),
				'id'   => 'singular_name',
				'type' => 'text_medium',
				'attributes' => array(
					'required' => 'required',
				),
			)
		);

		$mbds_settings_metabox->add_group_field( 'taxonomies', array(
				'name' => __('Plural Name', 'mooberry-book-manager'),
				'id'   => 'plural_name',
				'type' => 'text_medium',
				'attributes' => array(
					'required' => 'required',
				),
			)
		);

		$mbds_settings_metabox->add_group_field( 'taxonomies', array(
				'name' => __('Taxonomy Grid Heading', 'mooberry-book-manager'),
				'id'   => 'heading',
				'desc'  => __( 'This will be the title on the Taxonomy Grid page. You can use %s where you want the term.', 'mooberry-book-manager'),
				'type' => 'text_medium',
				'attributes' => array(
					'required' => 'required',
				),
			)
		);







		return apply_filters( 'mbds_settings_core_metabox', $mbds_settings_metabox, $this->page, $this->tab );

	}


	protected function set_tabs() {
		$this->tabs = array(
			'mbds_import_export' => array(
				'import'          => __( 'Import', 'mooberry-story' ),
				'export'          => __( 'Export', 'mooberry-story' ),
				'import_novelist' => __( 'Import from Novelist', 'mooberry-story' )
			)
		);
	}


	/**
	 * Register settings notices for display
	 *
	 * @since  0.1.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
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
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
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
	 *  @since 3.0
	 *  @param [string] $old_value
	 *  @param [string] $new_value
	 *
	 *  @access public
	 */
	public function options_updated( $old_value, $new_value ) {



	    if ( array_key_exists( 'taxonomies', $new_value ) ) {
	       MBDSP()->register_taxonomies( $new_value[ 'taxonomies' ] );
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






