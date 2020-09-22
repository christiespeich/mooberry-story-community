<?php


class Mooberry_Story_Community_Settings_Tab {

	protected $settings_page;
	protected $tab_group;
	protected $tab_title;
	protected $display_cb;
	protected $parent_slug;
	protected $title;

/*
	protected $metabox_id;
	protected $option_key;*/


	public function __construct( $metabox_id, $option_key, $tab_title, Mooberry_Story_Community_Tabbed_Settings_Page $settings_page ) {
		//$this->settings_page = $settings_page;
		$this->settings_page = new Mooberry_Story_Community_Settings_Page( $metabox_id, $option_key);
		$this->tab_group = $settings_page->tab_group;
		$this->tab_title = $tab_title;
		$this->display_cb = $settings_page->display_cb;
		$this->parent_slug = $settings_page->option_key;
		$this->title = $settings_page->title;

		$this->metabox_id = $metabox_id;
		$this->option_key = $option_key;


	}

	public function create_metabox( $args = array() ) {
		$args = array_merge ( $args, array(
			'parent_slug' => $this->parent_slug,
			'tab_group' => $this->tab_group,
			'tab_title' => $this->tab_title,
			'display_cb' => $this->display_cb,
			'title' => $this->title,
		));

		$this->settings_page->create_metabox($args);
	}

	public function add_field ($args ) {
		return $this->settings_page->add_field($args);
	}

	public function add_group_field( $field_id, $args, $position = 0 ) {
		return $this->settings_page->add_group_field( $field_id, $args, $position );
	}

	public function __get( $name ) {
		if ( property_exists($this, $name)) {
			return $this->{$name};
		}
	}
}
