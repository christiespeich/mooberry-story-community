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

		// break up the description into multiple sections to keep the HTML
		// out of the translatable text
		$description1 = __( 'This will be used to create a website URL for a page that displays all stories within a taxonomy item.  Text entered in these fields will be converted to "friendly URLs" by making them lower-case, removing the spaces, etc.', 'mooberry-story-community' );
		$description2 = '<b>' . __( 'NOTE:', 'mooberry-story-community' ) . '</b> ' . __( 'Wordpress reserved terms are not allowed here.', 'mooberry-story-community' );
		$description4 = __( 'Reserved Terms', 'mooberry-story-community' );
		$description5 = __( 'See a list of reserved terms.', 'mooberry-story-community' );

		$description = $description1 .
		               '<br><br>' .
		               $description2 .
		               ' <a href="" onClick="window.open(\'' . MOOBERRY_STORY_COMMUNITY_PLUGIN_URL . 'admin/partials/reserved_terms.php' . '\', \'' . $description4 .
		               '\',  \'width=460, height=300, left=550, top=250, scrollbars=yes\'); return false;">' .
		               $description5 .
		               '</a>';


		$this->add_group_field( 'taxonomies', array(
				'id'              => 'slug',
				'name'            => 'Website URL Slug',
				'desc'            => $description,
				'sanitization_cb' => array( $this, 'sanitize_slug' ),
				'type'            => 'text_medium',
				'attributes'      => array( 'required' => 'required' )
			)
		);


		$this->add_group_field( 'taxonomies', array(
				'name'       => __( 'Should this taxonomy be hierarchical?', 'mooberry-story-premium' ),
				'desc'       => __( 'Hierarchical taxonomies allow you to set parent/child relationship between items. For example, the Wordpress Categories are hierarchical.' ),
				'id'         => 'hierarchical',
				'type'       => 'select',
				'options'    => array( '' => '', 'no' => 'No', 'yes' => 'Yes' ),
				'attributes' => array(
					'required' => 'required',
				),
			)
		);

		$this->add_group_field( 'taxonomies', array(
				'name'       => __( 'Can stories be assigned to multiple items of this taxonomy, or just a single one?', 'mooberry-story-premium' ),
				'id'         => 'multiple',
				'type'       => 'select',
				'options'    => array( '' => '', 'yes' => 'Multiple', 'no' => 'Only one' ),
				'attributes' => array(
					'required' => 'required',
				),
			)
		);

		$this->add_group_field( 'taxonomies', array(
				'name'       => __( 'Display this field on the Table of Contents Page?', 'mooberry-story-premium' ),
				'id'         => 'display_toc',
				'type'       => 'select',
				'options'    => array(
					''    => '',
					'yes' => __( 'Yes', 'mooberry-story-premium' ),
					'no'  => __( 'No', 'mooberry-story-premium' ),
				),
				'attributes' => array(
					'required' => 'required',
				),
			)
		);


		$this->add_group_field( 'taxonomies', array(
				'name'       => __( 'Are users required to enter an item for this taxonomy?', 'mooberry-story-premium' ),
				'id'         => 'required',
				'type'       => 'select',
				'options'    => array(
					''    => '',
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
				if ( isset( $taxonomy['roles_can_add'] ) ) {
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
			//new Mooberry_Story_Community_Story_CPT();
			global $mbdsc_story_factory;
			$mbdsc_story_factory->create_story_cpt();
//			Mooberry_Story_Community_Factory_Generator::create_story_factory()->create_story_cpt();

//			flush_rewrite_rules();
			update_option('mbdsc_flush_rules', true);
		}

	}

	function sanitize_slug( $meta_value, $args, $object ) {

		// make sure none of the fields are blank
		if ( ! isset( $meta_value ) || trim( $meta_value ) == '' ) {
			// default to the field id as a last resort

			//$custom_fields = MBDBBS()->custom_fields_options->custom_fields;
			$meta_value = sanitize_title( $args['default'] );
		}
		$reserved_terms = array(
			'attachment',
			'attachment_id',
			'author',
			'author_name',
			'calendar',
			'cat',
			'category',
			'category__and',
			'category__in',
			'category__not_in',
			'category_name',
			'comments_per_page',
			'comments_popup',
			'customize_messenger_channel',
			'customized',
			'cpage',
			'day',
			'debug',
			'error',
			'exact',
			'feed',
			'hour',
			'link_category',
			'm',
			'minute',
			'monthnum',
			'more',
			'name',
			'nav_menu',
			'nonce',
			'nopaging',
			'offset',
			'order',
			'orderby',
			'p',
			'page',
			'page_id',
			'paged',
			'pagename',
			'pb',
			'perm',
			'post',
			'post__in',
			'post__not_in',
			'post_format',
			'post_mime_type',
			'post_status',
			'post_tag',
			'post_type',
			'posts',
			'posts_per_archive_page',
			'posts_per_page',
			'preview',
			'robots',
			's',
			'search',
			'second',
			'sentence',
			'showposts',
			'static',
			'subpost',
			'subpost_id',
			'tag',
			'tag__and',
			'tag__in',
			'tag__not_in',
			'tag_id',
			'tag_slug__and',
			'tag_slug__in',
			'taxonomy',
			'tb',
			'term',
			'terms',
			'theme',
			'title',
			'type',
			'w',
			'withcomments',
			'withoutcomments',
			'year',
		);;
		if ( in_array( $meta_value, $reserved_terms ) ) {
			//show a message
			$msg = '"' . $meta_value . '" ' . __( 'is a reserved term and not allowed. This field was not saved.', 'mbm-book-shop' );
			add_settings_error( $this->option_key . '-error', '', $msg, 'error' );
			settings_errors( $this->option_key . '-error' );

			// return the original value
			return sanitize_title( $object->value );
		}

		// entered value is OK. Sanitize it and return it
		return sanitize_title( $meta_value );
	}

}
