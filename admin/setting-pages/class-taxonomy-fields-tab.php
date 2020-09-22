<?php


class Mooberry_Story_Community_Taxonomy_Fields_Tab extends Mooberry_Story_Community_Settings_Tab {

	public function __construct( Mooberry_Story_Community_Tabbed_Settings_Page $setting_page ) {
		parent::__construct( 'mbdsc_taxonomy_fields_metabox', 'mbdsc_taxonomy_fields_options', 'Taxonomy Fields', $setting_page );

		$this->create_metabox();
		$this->add_fields();

		add_action( 'update_option_mbdsc_taxonomy_fields_options', array( $this, 'options_updated' ), 10, 2 );
	}


	/**
	 * Add the options metabox to the array of metaboxes
	 * Choose which metabox based on $tab
	 *
	 * @since  3.0
	 */
	function add_fields() {


		$this->add_field( array(
				'id'   => 'author_page_title',
				'name' => __( 'TAXONOMY FIELDS', 'mooberry-story-premium' ),
				'type' => 'title',
			)
		);

		$this->add_field( array(
				'id'      => 'taxonomies',
				'type'    => 'group',
				'desc'    => __( 'Taxonomies organize stories. Good candidates for Taxonomy fields are Genres, Series, Tags, etc.', 'mooberry-story-premium' ),
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

		$this->add_group_field( 'taxonomies', array(
				'name'       => __( 'Singular Name', 'mooberry-story-premium' ),
				'id'         => 'singular_name',
				'type'       => 'text_medium',
				'attributes' => array(
					'required' => 'required',
				),
			)
		);

		$this->add_group_field( 'taxonomies', array(
				'name'       => __( 'Plural Name', 'mooberry-story-premium' ),
				'id'         => 'plural_name',
				'type'       => 'text_medium',
				'attributes' => array(
					'required' => 'required',
				),
			)
		);

				$this->add_group_field( 'taxonomies', array(
				'name'       => __( 'Should this taxonomy be hierarchical?', 'mooberry-story-premium' ),
				'desc'      =>  __('Hierarchical taxonomies allow you to set parent/child relationship between items. For example, the Wordpress Categories are hierarchical.'),
				'id'         => 'hierarchical',
				'type'       => 'select',
				'options'   =>  array(''=>'', 'no'=>'No', 'yes'=>'Yes'),
				'attributes' => array(
					'required' => 'required',
				),
			)
		);

					$this->add_group_field( 'taxonomies', array(
				'name'       => __( 'Can stories be assigned to multiple items of this taxonomy, or just a single one?', 'mooberry-story-premium' ),
				'id'         => 'multiple',
				'type'       => 'select',
				'options'   =>  array(''=>'','yes'=>'Multiple', 'no'=>'Only one'),
				'attributes' => array(
					'required' => 'required',
				),
			)
		);

		$this->add_group_field( 'taxonomies', array(
				'name'       => __( 'Display this field on the Table of Contents Page?', 'mooberry-story-premium' ),
				'id'         => 'display_toc',
				'type'       => 'select',
				'options'    => array(''=>'',
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

		$this->add_group_field( 'taxonomies', array(
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

	function options_updated( $old_value, $new_value ) {
		if ( array_key_exists( 'taxonomies', $new_value ) ) {


			// add/remove capabilities to roles
			foreach ( $new_value['taxonomies'] as $taxonomy ) {
				$single = isset( $taxonomy['singular_name'] ) ? sanitize_text_field( $taxonomy['singular_name'] ) : '';
				$tax    = 'mbdsc_' . sanitize_title( $single );

				$roles = array();
				if (  isset( $taxonomy['roles_can_add'] ) ) {
					$roles = is_array( $taxonomy['roles_can_add'] ) ? $taxonomy['roles_can_add'] : array( $taxonomy['roles_can_add'] );
				}

				global $wp_roles;
				$user_roles = $wp_roles->get_names();
				if ( ! is_array( $user_roles ) ) {
					$user_roles = array( $user_roles );
				}

				foreach ( $user_roles as $user_role => $name ) {
					$role = get_role( $user_role );
					if ( $role ) {
						if ( in_array( $role->name, $roles ) ) {
							$role->add_cap( 'add_' . $tax . '_terms' );
							$role->add_cap( 'edit_' . $tax . '_terms' );
							$role->add_cap( 'delete_' . $tax . '_terms' );
						} else {
							$role->remove_cap( 'add_' . $tax . '_terms' );
							$role->remove_cap( 'edit_' . $tax . '_terms' );
							$role->remove_cap( 'delete_' . $tax . '_terms' );
						}
						// all roles can assign
						$role->add_cap( 'assign_' . $tax . '_terms' );
					}
				}
			}

			// mbdsc_register_taxonomies( $new_value['taxonomies'], new Mooberry_Story_Community_Story_CPT() );

			// re-register the CPT to register the taxonomies
			new Mooberry_Story_Community_Story_CPT();
			flush_rewrite_rules();
		}

	}

}
