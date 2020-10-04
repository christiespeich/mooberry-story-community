<?php


class Mooberry_Story_Community_Custom_Taxonomy {

	protected $taxonomy;
	protected $singular_name;
	protected $plural_name;
	protected $slug;
	protected $roles_can_add;
	protected $is_required;
	protected $is_hierarchical;
	protected $allow_multiple;
	protected $display_toc;


	public function __construct( $custom_taxonomy ) {
		$this->singular_name   = isset( $custom_taxonomy['singular_name'] ) ? sanitize_text_field( $custom_taxonomy['singular_name'] ) : '';
		$this->plural_name     = isset( $custom_taxonomy['plural_name'] ) ? sanitize_text_field( $custom_taxonomy['plural_name'] ) : '';
		$this->is_hierarchical = isset( $custom_taxonomy['hierarchical'] ) && $custom_taxonomy['hierarchical'] === 'yes';
		$this->taxonomy        = 'mbdsc_' . sanitize_title( $this->singular_name );
		$this->slug            = isset( $custom_taxonomy['slug'] ) ? sanitize_text_field( $custom_taxonomy['slug'] ) : '';
		$this->roles_can_add   = $this->load_roles_can_add( $custom_taxonomy );
		$this->is_required     = isset( $custom_taxonomy['required'] ) && $custom_taxonomy['required'] === 'yes';
		$this->allow_multiple  = isset( $custom_taxonomy['multiple'] ) && $custom_taxonomy['multiple'] === 'yes';
		$this->display_toc     = isset( $custom_taxonomy['display_toc'] ) && $custom_taxonomy['display_toc'] === 'yes';
	}

	protected function load_roles_can_add( $custom_taxonomy ) {
		$roles = array();
		if ( isset( $custom_taxonomy['roles_can_add'] ) && is_array( $custom_taxonomy['roles_can_add'] ) ) {
			foreach ( $custom_taxonomy['roles_can_add'] as $role ) {
				$wp_role = get_role( $role );
				if ( isset( $wp_role ) ) {
					$roles[] = $role;
				}
			}
		}

		return $roles;

	}

	public function __get( $name ) {
		if ( property_exists($this, $name)) {
			return $this->$name;
		}
		return '';


	}
}
