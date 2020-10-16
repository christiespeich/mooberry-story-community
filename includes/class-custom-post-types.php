<?php

/**
 * The Mooberry_Story_Community_CPT class is the base class responsible for creating and managing
 * Custom Post Types
 *
 * @package MBDSC
 */

/**
 * The Mooberry_Story_Community_CPT class is the base class responsible for creating and managing
 * Custom Post Types
 *
 *
 * @since    4.0.0
 */
abstract class Mooberry_Story_Community_CPT {

	//protected $columns;

	protected $metaboxes;
	protected $quick_edit_fields;
	protected $bulk_edit_fields;
	protected $post_type;
	protected $taxonomies;
	protected $data_object;
	protected $singular_name;
	protected $plural_name;
	protected $args;

	protected $single;
	protected $plural;

	protected $reader_level;
	protected $moderated_author_level;
	protected $author_level;
	protected $moderator_level;

	abstract public function create_metaboxes();


	public function __construct( $post_type ) {

		$this->args                    = array();
		$this->taxonomies              = array();
		$this->metaboxes               = array();
		$this->quick_edit_fields       = array();
		$this->bulk_edit_fields        = array();
		$this->post_type               = $post_type;
		$this->data_object             = null;
		$this->singular_name           = '';
		$this->plural_name             = '';
		$this->default_single_template = 'default';


		add_action( 'init', array( $this, 'register' ) );
		add_action( 'cmb2_admin_init', array( $this, 'create_metaboxes' ) );
		add_filter( 'wpseo_metabox_prio', array( $this, 'reorder_wpseo' ) );
		/*	add_filter('cmb2_override_meta_remove', array( $this, 'save_meta_data' ), 10, 2);
			add_filter('cmb2_override_meta_save', array( $this, 'save_meta_data' ), 10, 2);
			add_filter('cmb2_override_meta_value', array( $this, 'get_meta_data'), 10, 3);
		*/

		add_action( 'quick_edit_custom_box', array( $this, 'quick_edit' ), 1, 2 );
		add_action( 'save_post', array( $this, 'quick_edit_save_post' ), 10, 2 );

		// prioirty 40 to make it run after override_meta_save
		//add_action('save_post', array( $this, 'save' ), 40);

		add_action( 'bulk_edit_custom_box', array( $this, 'bulk_edit' ), 1, 2 );
		add_action( 'wp_ajax_bulk_quick_save_bulk_edit', array( $this, 'bulk_edit_save_post' ) );

		add_action( 'admin_notices', array( $this, 'admin_notice' ), 0 );

		add_filter( 'manage_' . $this->post_type . '_posts_columns', array( $this, 'set_custom_columns' ) );
		add_action( 'manage_' . $this->post_type . '_posts_custom_column', array(
			$this,
			'display_custom_columns'
		), 10, 2 );
		add_filter( 'manage_edit-' . $this->post_type . '_sortable_columns', array( $this, 'sortable_columns' ) );

		add_action( 'pre_get_posts', array( $this, 'columns_orderby' ) );


	}

	public function set_custom_columns( $columns ) {
		return $columns;
	}

	public function display_custom_columns( $column, $post_id ) {
	}

	public function sortable_columns( $columns ) {
		return $columns;
	}

	public function columns_orderby( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}
	}

	public function register() {

		$defaults = array(
			'label'             => $this->plural_name,
			//	'public' => true,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'menu_position'     => 20,
			'show_in_nav_menus' => true,
			'has_archive'       => true,
			'map_meta_cap'      => true,
			'hierarchical'      => false,
			'rewrite'           => false,
			'query_var'         => true,
			'labels'            => array(
				'name'                  => $this->plural_name,
				'singular_name'         => $this->singular_name,
				'menu_name'             => $this->plural_name,
				'all_items'             => sprintf( __( 'All %s', 'mooberry-story-community' ), $this->plural_name ),
				'add_new'               => __( 'Add New', 'mooberry-story-community' ),
				'add_new_item'          => sprintf( __( 'Add New %s', 'mooberry-story-community' ), $this->singular_name ),
				'edit'                  => __( 'Edit', 'mooberry-story-community' ),
				'edit_item'             => sprintf( __( 'Edit %s', 'mooberry-story-community' ), $this->singular_name ),
				'new_item'              => sprintf( __( 'New %s', 'mooberry-story-community' ), $this->singular_name ),
				'view'                  => sprintf( __( 'View %s', 'mooberry-story-community' ), $this->singular_name ),
				'view_item'             => sprintf( __( 'View %s', 'mooberry-story-community' ), $this->singular_name ),
				'search_items'          => sprintf( __( 'Search %s', 'mooberry-story-community' ), $this->plural_name ),
				'not_found'             => sprintf( __( 'No %s Found', 'mooberry-story-community' ), $this->plural_name ),
				'not_found_in_trash'    => sprintf( __( 'No %s Found in Trash', 'mooberry-story-community' ), $this->plural_name ),
				'parent'                => sprintf( __( 'Parent %s', 'mooberry-story-community' ), $this->singular_name ),
				'filter_items_list'     => sprintf( __( 'Filter $s List', 'mooberry-story-community' ), $this->singular_name ),
				'items_list_navigation' => sprintf( __( '%s List Navigation', 'mooberry-story-community' ), $this->singular_name ),
				'items_list'            => sprintf( __( '%s List', 'mooberry-story-community' ), $this->singular_name ),
				'view items'            => sprintf( __( 'View %s', 'mooberry-story-community' ), $this->plural_name ),
				'attributes'            => sprintf( __( '%s Attributes', 'mooberry-story-community' ), $this->singular_name ),
			),
		);


		$this->args = wp_parse_args( $this->args, $defaults );
		register_post_type( $this->post_type, apply_filters( $this->post_type . '_cpt', $this->args ) );


		foreach ( $this->taxonomies as $taxonomy ) {
			$taxonomy->register();
		}

	}


	public function add_post_class( $classes ) {
		if ( get_post_type() == $this->post_type ) {
			if ( ! in_array( 'post', $classes ) ) {
				$classes[] = 'post';
			}
		}

		return $classes;
	}

	public function add_taxonomy( $taxonomy ) {
		$this->taxonomies[ $taxonomy->taxonomy ] = $taxonomy;
	}

	public function reorder_wpseo( $priority ) {
		if ( get_post_type() == $this->post_type ) {
			return 'default';
		} else {
			return $priority;
		}
	}

	public function quick_edit( $column_name, $post_type ) {
		if ( array_key_exists( $column_name, $this->quick_edit_fields ) ) {
			$field = $this->quick_edit_fields[ $column_name ];
			$this->quick_bulk_edit( $column_name, $post_type, $field );
		}
	}

	public function bulk_edit( $column_name, $post_type ) {
		if ( array_key_exists( $column_name, $this->bulk_edit_fields ) ) {
			$field = $this->bulk_edit_fields[ $column_name ];
			$this->quick_bulk_edit( $column_name, $post_type, $field );
		}
	}

	private function quick_bulk_edit( $column_name, $post_type, $field ) {
		if ( $post_type != $this->post_type ) {
			return;
		}

		$defaults = array(
			'fieldset_class' => '',
			'fieldset_style' => '',
			'label'          => '',
			'field_class'    => '',
			'field'          => '',
			'description'    => '',
		);
		$field    = wp_parse_args( $field, $defaults );

		?>

        <fieldset style="<?php echo $field['fieldset_style']; ?>" class="<?php echo $field['fieldset_class']; ?>">
            <div class="inline-edit-col">
                <label>
                    <span class="title"><?php echo $field['label']; ?></span>
                    <span class="<?php echo $field['field_class']; ?>">
										<?php echo $field['field']; ?>
										<?php echo $field['description']; ?>
								</span>
                </label>
            </div>
        </fieldset>
		<?php
	}

	public function quick_edit_save_post( $post_id, $post ) {

		// pointless if $_POST is empty (this happens on bulk edit)
		if ( empty( $_POST ) ) {
			return $post_id;
		}

		// bail if not a quick edit
		if ( ! isset( $_POST['_inline_edit'] ) ) {
			return $post_id;
		}

		// verify quick edit nonce
		if ( isset( $_POST['_inline_edit'] ) && ! wp_verify_nonce( $_POST['_inline_edit'], 'inlineeditnonce' ) ) {
			return $post_id;
		}


		// don't save for autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// dont save for revisions
		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		if ( $post->post_type !== $this->post_type ) {
			return $post_id;
		}

		$fields = array_keys( $this->quick_edit_fields );


		foreach ( $fields as $postmeta ) {
			if ( array_key_exists( $postmeta, $_POST ) ) {
				$value = $_POST[ $postmeta ];
				$value = $this->handle_quick_edit_data( $postmeta, $value );

			}
		}


	}

	public function bulk_edit_save_post() {


		// we need the post IDs
		$post_ids = ( isset( $_POST['post_ids'] ) && ! empty( $_POST['post_ids'] ) ) ? $_POST['post_ids'] : null;

		// if we have post IDs
		if ( empty( $post_ids ) || ! is_array( $post_ids ) ) {
			return;
		}

		// get the custom fields
		$custom_fields = array_keys( $this->bulk_edit_fields );
		// update for each post ID
		foreach ( $post_ids as $post_id ) {
			if ( get_post_type( $post_id ) != $this->post_type ) {
				return;
			}


			foreach ( $custom_fields as $field ) {
				// if it has a value, doesn't update if empty on bulk
				if ( isset( $_POST[ $field ] ) && ! empty( $_POST[ $field ] ) ) {
					//$this->data_object->set_by_postmeta( $field, $_POST[ $field ] );
				}

			}

			//$this->data_object->save();
		}

	}

	protected function handle_quick_edit_data( $field, $value ) {
		return $value;
	}

	protected function is_array_element_set( $fieldname, $arrayname ) {
		return ( array_key_exists( $fieldname, $arrayname ) && isset( $arrayname[ $fieldname ] ) && trim( $arrayname[ $fieldname ] ) != '' );
	}

	// if it's the last element and both sides of the check are empty, ignore the error
	// because CMB2 will automatically delete it from the repeater group
	protected function allow_blank_last_elements( $field1, $field2, $fieldname, $key, $flag ) {
		if ( ! $field1 && ! $field2 ) {
			// to the end of the array
			end( $_POST[ $fieldname ] );
			if ( $key === key( $_POST[ $fieldname ] ) ) {
				return false;
			}
		}

		return $flag;
	}

	public function kses_allowed_html( $allowed_tags, $context ) {

		if ( $context != $this->post_type ) {
			return $allowed_tags;
		}
		// start with post allowed tags
		global $allowedposttags;

		$allowed_tags           = $allowedposttags;
		$allowed_tags['iframe'] = array(
			'src'             => array(),
			'height'          => array(),
			'width'           => array(),
			'frameborder'     => array(),
			'allowfullscreen' => array(),
		);

		return $allowed_tags;
	}


	protected function display_msg_if_invalid( $flag, $fieldname, $group, $message ) {
		// on attempting to publish - check for completion and intervene if necessary
		if ( ( isset( $_POST['publish'] ) || isset( $_POST['save'] ) ) && $_POST['post_status'] == 'publish' ) {
			//  don't allow publishing while any of these are incomplete
			if ( $flag ) {
				// set the message
				$itemID = array_search( $group, $_POST[ $fieldname ] );
				$itemID ++;
				$message = sprintf( $message, $itemID );
				$this->error_message( $message );
			}
		}
	}

	// this takes post_id as a null for book shop book limits errors
	protected function error_message( $message, $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = $_POST['post_ID'];
		}
		// set the message
		$notice             = get_option( 'mbdsc_notice' );
		$notice[ $post_id ] = '<span class="mbdsc-validation-error">' . $message . '</span>';
		update_option( 'mbdsc_notice', $notice );

		// change it to pending not updated
		global $wpdb;
		$wpdb->update( $wpdb->posts, array( 'post_status' => 'draft' ), array( 'ID' => $post_id ) );

		// filter the query URL to change the published message
		add_filter( 'redirect_post_location', function ( $location ) {
			return esc_url_raw( add_query_arg( "message", "0", $location ) );
		} );
	}

	// public for backwards comaptibility for MA
	public function validate_all_group_fields( $groupname, $fieldIDname, $fields, $message ) {
		do_action( 'mbdsc_before_validate' . $groupname );

		$flag = false;

		foreach ( $_POST[ $groupname ] as $key => $group ) {
			// both fields must be filled in
			$is_field1 = $this->is_array_element_set( $fieldIDname, $group ) && $group[ $fieldIDname ] != '0';
			$is_others = true;
			foreach ( $fields as $field ) {
				if ( ! $this->is_array_element_set( $field, $group ) ) {
					$is_others = false;
					break;
				}
			}
			$flag = ! ( $is_field1 && $is_others );

			// if it's the last element and both sides of the check are empty, ignore the error
			// because CMB2 will automatically delete it from the repeater group
			$flag = $this->allow_blank_last_elements( $is_field1, $is_others, $groupname, $key, $flag );

			if ( $flag ) {
				break;
			}
		}
		do_action( 'mbdsc_validate' . $groupname . '_before_msg', $flag, $group );

		$this->display_msg_if_invalid( $flag, $groupname, $group, apply_filters( 'mbdsc_validate' . $groupname . '_msg', $message ) );
		do_action( 'mbdsc_validate' . $groupname . '_after_msg', $flag, $group );
	}

	protected function get_wysiwyg_output( $content ) {
		global $wp_embed;

		$content = $wp_embed->autoembed( $content );
		$content = $wp_embed->run_shortcode( $content );
		$content = wpautop( $content );
		$content = do_shortcode( $content );


		return $content;
	}

	protected function sanitize_field( $field ) {
		return strip_tags( stripslashes( $field ) );
	}


	/**
	 * Grab the template set in the options for the book page and tax grid
	 *
	 *
	 * Attempts to pull the template from the options
	 *
	 * In the case that the options aren't set or the template selected
	 * doesn't exist, default to the theme's single template
	 *
	 *
	 * @access public
	 *
	 * @param string $template
	 *
	 * @return string $template
	 * @since  3.5.4 Checks if this is a search and bails if so
	 *
	 * @since  2.1
	 * @since  3.0 Added support for tax grid template as well. Changed from single_template to template_include filter
	 */
	public function single_template( $template ) {
		// if a search, return what we got in
		global $wp_query;
		if ( $wp_query->is_search() ) {
			return $template;
		}

		if ( get_post_type() != $this->post_type ) {
			return $template;
		}

		// make sure it's the main query and not on the admin
		if ( is_main_query() && ! is_admin() ) {
			$default_template = $this->default_single_template;
		} else {
			return $template;
		}

		// if it's the default template, use the single.php template
		if ( $default_template == 'default' ) {
			$default_template = 'single.php';
		}

		// now get the file
		if ( isset( $default_template ) && $default_template != '' && $default_template != 'default' ) {

			// first check if there's one in the child theme
			$child_theme = get_stylesheet_directory();

			if ( file_exists( $child_theme . '/' . $default_template ) ) {
				return $child_theme . '/' . $default_template;
			} else {
				// if not get the parent theme
				$parent_theme = get_template_directory();

				if ( file_exists( $parent_theme . '/' . $default_template ) ) {
					return $parent_theme . '/' . $default_template;
				}
			}
		}

		// if everything fails, just return whatever came in
		return $template;

	}


	/**
	 * Admin Notices for Posts
	 *
	 * Displays error message generated by editing posts
	 * Uses options to save error messages between page loads
	 * Expects format option['mbdsc_notice'] = { $postID => $message }
	 *
	 *
	 * @access public
	 * @return void
	 * @since  1.0
	 */
	function admin_notice() {

		global $post;

		// only show on admin pages where there is a post id (ie editing a cpt)
		if ( $post ) {
			$notice = get_option( 'mbdsc_notice' );

			if ( empty( $notice ) ) {
				return '';
			}

			foreach ( $notice as $pid => $m ) {
				if ( $post->ID == $pid ) {
					echo apply_filters( 'mbdsc_post_admin_notice', '<div id="message" class="error"><p>' . $m . '</p></div>' );

					//make sure to remove notice after its displayed so its only displayed when needed.
					unset( $notice[ $pid ] );

					update_option( 'mbdsc_notice', $notice );

					break;
				}
			}
		}
	}

	protected function add_custom_fields( $custom_fields, $metabox ) {
		foreach ( $custom_fields as $custom_field ) {

			if ( $custom_field->is_disabled ) {
				continue;
			}

			$args = array(
				'name' => $custom_field->name,
				'id'   => 'mbdsc_custom_field_' . $custom_field->unique_id,
				'type' => $custom_field->type,
                'desc'  =>  $custom_field->description
			);

			if ( $custom_field->has_options ) {
				$args['options'] = $custom_field->get_options_list();
			}


			$metabox->add_field( apply_filters( 'mbdsc_story_' . $custom_field->unique_id . '_field', $args ) );
		}
	}

	public function remove_role_caps() {
	    foreach (
			array(
				'contributor',

				'administrator'
			) as $role
		) {
	        $role_obj = get_role($role);
			foreach ( $this->moderated_author_level as $capability ) {
				$role_obj->remove_cap( $capability );
			}
		}

		// author == author
		foreach (
			array(
				'author',

				'administrator'
			) as $role
		) {
		     $role_obj = get_role($role);
			foreach ( $this->author_level as $capability ) {
				  $role_obj->remove_cap( $capability );

			}
		}

		// moderator = editor
		foreach (
			array(
				'editor',
			//	MOOBERRY_STORY_COMMUNITY_ROLE_MODERATOR,
				MOOBERRY_STORY_COMMUNITY_ROLE_ADMIN,
				'administrator'
			) as $role
		) {
		      $role_obj = get_role($role);
			foreach ( $this->moderator_level as $capability ) {
				$role_obj->remove_cap( $capability );
			}
		}

    }


	public function set_up_roles() {
		// moderated author = contributor
		foreach (
			array(
				'contributor',
				//MOOBERRY_STORY_COMMUNITY_ROLE_MODERATED_AUTHOR,
				MOOBERRY_STORY_COMMUNITY_ROLE_AUTHOR,
				//MOOBERRY_STORY_COMMUNITY_ROLE_MODERATOR,
				MOOBERRY_STORY_COMMUNITY_ROLE_ADMIN,
				'administrator'
			) as $role
		) {
			foreach ( $this->moderated_author_level as $capability ) {
				get_role( $role )->add_cap( $capability );
			}
		}

		// author == author
		foreach (
			array(
				'author',
				MOOBERRY_STORY_COMMUNITY_ROLE_AUTHOR,
			//	MOOBERRY_STORY_COMMUNITY_ROLE_MODERATOR,
				MOOBERRY_STORY_COMMUNITY_ROLE_ADMIN,
				'administrator'
			) as $role
		) {
			foreach ( $this->author_level as $capability ) {
				get_role( $role )->add_cap( $capability );

			}
		}

		// moderator = editor
		foreach (
			array(
				'editor',
			//	MOOBERRY_STORY_COMMUNITY_ROLE_MODERATOR,
				MOOBERRY_STORY_COMMUNITY_ROLE_ADMIN,
				'administrator'
			) as $role
		) {
			foreach ( $this->moderator_level as $capability ) {
				get_role( $role )->add_cap( $capability );
			}
		}

	}

	/**
	 * Magic __get function to dispatch a call to retrieve a private property
	 *
	 * @since 1.0
	 */
	public function __get( $key ) {

		if ( method_exists( $this, 'get_' . $key ) ) {

			return call_user_func( array( $this, 'get_' . $key ) );

		} else {

			$ungettable_properties = array();

			if ( property_exists( $this, $key ) ) {

				if ( ! in_array( $key, $ungettable_properties ) ) {

					return $this->$key;

				}

			}

		}

		return new WP_Error( 'mbdb-invalid-property', sprintf( __( 'Can\'t get property %s', 'mooberry-story-community' ), $key ) );

	}


}
