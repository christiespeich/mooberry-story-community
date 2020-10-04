<?php


class Mooberry_Story_Community_Settings_Page {

	protected $metabox_id;
	protected $title;
	protected $menu_title;
	protected $option_key;
	protected $parent_slug;
	protected $metabox;

	public function __construct( $metabox_id, $option_key ) {
		$this->metabox_id = $metabox_id;
		$this->option_key = $option_key;

		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );
	}

	public function set_title( $value ) {
		$this->title = $value;
	}

	public function set_menu_title( $value ) {
		$this->menu_title = $value;
	}

	public function set_parent_slug( $value ) {
		$this->parent_slug = $value;
	}

	public function create_metabox( $args = array() ) {
		$args = array_merge( $args, array(
			'id'           => $this->metabox_id,
			'object_types' => array( 'options-page' ),
			'option_key'    =>  $this->option_key,
			'capability'      => 'manage_options',
			'message_cb' => array( $this, 'display_message'),
		) );

		if ( $this->title != '' ) {
			$args['title'] = $this->title;
		}

		if ( $this->menu_title != '' ) {
			$args['menu_title'] = $this->menu_title;
		}

		if ( $this->parent_slug != '' ) {
			$args['parent_slug'] = $this->parent_slug;
		}

		$this->metabox = new_cmb2_box( $args );

	}

	public function add_field( $args ) {
		return $this->metabox->add_field( $args );
	}

	public function add_group_field( $field_id, $args, $position = 0 ) {
		return $this->metabox->add_group_field( $field_id, $args, $position );
	}

	/**
	 * Register settings notices for display
	 *
	 * @since  3.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function settings_notices( $object_id, $updated ) {

		// validate inputs
		if ( $object_id !== $this->option_key || empty( $updated ) ) {
			return;
		}

		// show updated notice
		add_settings_error( $this->option_key . '-notices', '', __( 'Settings updated.', 'mooberry-story-community' ), 'updated' );
		//settings_errors( $this->option_key . '-notices' );
		settings_errors( $this->option_key . '-error' );
	}

	function __get( $name ) {
		if ( property_exists($this, $name)) {
			return $this->{$name};
		} else {
			return null;
		}
	}

}
