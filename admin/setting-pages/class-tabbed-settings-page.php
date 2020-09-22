<?php



	class Mooberry_Story_Community_Tabbed_Settings_Page extends Mooberry_Story_Community_Settings_Page {

	protected $tab_group;
	protected $tab_title;
	protected $display_cb;
	protected $tabs;

	public function __construct( $id, $options_key, $tab_group, $tab_title ) {

		parent::__construct( $id, $options_key );
		$this->tab_group = $tab_group;
		$this->tab_title = $tab_title;
		$this->display_cb = array( $this, 'tab_display' );
		/*
		parent::__construct( $id, $options_key );


		$this->tab_group = $tab_group;
		$this->tabs = array();
		//$this->tab_title = $tab_title;


		$this->display_cb = array( $this, 'tab_display' );
		$this->create_metabox();*/
	}

	public function create_metabox( $args = array() ) {
		$args = array_merge ( $args, array(
			'tab_group'  => $this->tab_group,
			'tab_title' =>  $this->tab_title,
			'display_cb' => $this->display_cb,
		) );
		parent::create_metabox( $args );
	}

	protected function add_tab( Mooberry_Story_Community_Settings_Tab $tab ) {
		$this->tabs[] = $tab;
	}

	public function tab_display( $cmb_options ) {
		$tabs             = $this->get_tabs( $cmb_options );
//		$tabs = $this->tabs;
		$option_key       = $cmb_options->option_key;
		$admin_title      = get_admin_page_title();
		$cmb_id           = $cmb_options->cmb->cmb_id;
		$save_button_text = $cmb_options->cmb->prop( 'save_button' );
		include MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'admin/partials/setting-page-tabs.php';

	}


	/**
	 * Gets navigation tabs array for CMB2 options pages which share the given
	 * display_cb param.
	 *
	 * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
	 *
	 * @return array Array of tab information.
	 */
	function get_tabs( $cmb_options ) {
		$tab_group = $cmb_options->cmb->prop( 'tab_group' );
		$tabs      = array();
		foreach ( CMB2_Boxes::get_all() as $cmb_id => $cmb ) {
			if ( $tab_group === $cmb->prop( 'tab_group' ) ) {
				$tabs[ $cmb->options_page_keys()[0] ] = $cmb->prop( 'tab_title' )
					? $cmb->prop( 'tab_title' )
					: $cmb->prop( 'title' );
			}
		}

		return $tabs;

	}

	function __get( $name ) {
		if ( property_exists($this, $name)) {
			return $this->{$name};
		} else {
			return null;
		}
	}

}
