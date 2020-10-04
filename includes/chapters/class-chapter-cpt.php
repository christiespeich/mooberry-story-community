<?php


class Mooberry_Story_Community_Chapter_CPT extends Mooberry_Story_Community_CPT {

	public function __construct() {
		parent::__construct( 'mbdsc_chapter' );

		$this->plural_name   = 'Chapters';
		$this->singular_name = 'Chapter';
		$this->single        = 'mbdsc_chapter';
		$this->plural        = 'mbdsc_chapters';

		$this->args = array(
			'menu_icon' => 'data:image/svg+xml;base64,PHN2ZyBpZD0iQ2FwYV8xIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA1MTIgNTEyIiBoZWlnaHQ9IjUxMiIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHdpZHRoPSI1MTIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGc+PGc+PHBhdGggZD0ibTI5NiAzNi4wOWMtMjIuMDkgMC00MCAxNy45MS00MCA0MCAwLTIyLjA5LTE3LjkxLTQwLTQwLTQwaC0yMDZ2MzYwaDQ1MmMyMi4wOSAwIDQwLTE3LjkxIDQwLTQwdi0zMjB6IiBmaWxsPSIjZWVmYWZmIi8+PHBhdGggZD0ibTUwMiAzNi4wOXYzMjBjMC0yMy43ODMtMjAuMzYzLTQwLTQyLjE0LTQwaC0zNy44NmMxMy45NzEtNDguODk3IDQ5LjI3OS0xNzIuNDc0IDgwLTI4MHoiIGZpbGw9IiNhNGNhZmYiLz48cGF0aCBkPSJtNTAxLjgyIDQ0Ny42Mi0yOC4yOSAyOC4yOS00Mi40Mi00Mi40MyAyOC4yOC0yOC4yOHoiIGZpbGw9IiNmZjkyNTYiLz48cGF0aCBkPSJtNDU5LjM5IDQwNS4yLTI4LjI4IDI4LjI4Yy0xNC4wMjEtMTQuMDIxLTEwMC43MDYtMTAwLjcwNi0xMTMuMTQtMTEzLjE0bC0xNC4xNC00Mi40MiA0Mi40MiAxNC4xNGMyMi4yMzMgMjIuMjM0IDEwOC4wOTkgMTA4LjA5OSAxMTMuMTQgMTEzLjE0eiIgZmlsbD0iI2ZmZWU4MCIvPjwvZz48Zz48Y2lyY2xlIGN4PSIxOTYiIGN5PSIzMzYuMDkiIHI9IjEwIi8+PHBhdGggZD0ibTUwIDEwNi4wOWg4NmM1LjUyMyAwIDEwLTQuNDc3IDEwLTEwcy00LjQ3Ny0xMC0xMC0xMGgtODZjLTUuNTIzIDAtMTAgNC40NzctMTAgMTBzNC40NzcgMTAgMTAgMTB6Ii8+PHBhdGggZD0ibTI5NiAxMDYuMDloODZjNS41MjMgMCAxMC00LjQ3NyAxMC0xMHMtNC40NzctMTAtMTAtMTBoLTg2Yy01LjUyMyAwLTEwIDQuNDc3LTEwIDEwczQuNDc3IDEwIDEwIDEweiIvPjxwYXRoIGQ9Im01MCAxNDYuMDloMTY2YzUuNTIzIDAgMTAtNC40NzcgMTAtMTBzLTQuNDc3LTEwLTEwLTEwaC0xNjZjLTUuNTIzIDAtMTAgNC40NzctMTAgMTBzNC40NzcgMTAgMTAgMTB6Ii8+PHBhdGggZD0ibTUwIDE4Ni4wOWgxNjZjNS41MjMgMCAxMC00LjQ3NyAxMC0xMHMtNC40NzctMTAtMTAtMTBoLTE2NmMtNS41MjMgMC0xMCA0LjQ3Ny0xMCAxMHM0LjQ3NyAxMCAxMCAxMHoiLz48cGF0aCBkPSJtNTAgMjI2LjA5aDE2NmM1LjUyMyAwIDEwLTQuNDc3IDEwLTEwcy00LjQ3Ny0xMC0xMC0xMGgtMTY2Yy01LjUyMyAwLTEwIDQuNDc3LTEwIDEwczQuNDc3IDEwIDEwIDEweiIvPjxwYXRoIGQ9Im01MCAyNjYuMDloMTY2YzUuNTIzIDAgMTAtNC40NzcgMTAtMTBzLTQuNDc3LTEwLTEwLTEwaC0xNjZjLTUuNTIzIDAtMTAgNC40NzctMTAgMTBzNC40NzcgMTAgMTAgMTB6Ii8+PHBhdGggZD0ibTUwIDMwNi4wOWgxNjZjNS41MjMgMCAxMC00LjQ3NyAxMC0xMHMtNC40NzctMTAtMTAtMTBoLTE2NmMtNS41MjMgMC0xMCA0LjQ3Ny0xMCAxMHM0LjQ3NyAxMCAxMCAxMHoiLz48cGF0aCBkPSJtNTAgMzQ2LjA5aDEwNmM1LjUyMyAwIDEwLTQuNDc3IDEwLTEwcy00LjQ3Ny0xMC0xMC0xMGgtMTA2Yy01LjUyMyAwLTEwIDQuNDc3LTEwIDEwczQuNDc3IDEwIDEwIDEweiIvPjxwYXRoIGQ9Im01MDIgMjYuMDloLTIwNmMtMTYuMzM5IDAtMzAuODcgNy44NzgtNDAgMjAuMDM1LTkuMTMtMTIuMTU2LTIzLjY2MS0yMC4wMzUtNDAtMjAuMDM1aC0yMDZjLTUuNTIzIDAtMTAgNC40NzctMTAgMTB2MzYwYzAgNS41MjMgNC40NzcgMTAgMTAgMTBoNzhjNS41MjMgMCAxMC00LjQ3NyAxMC0xMHMtNC40NzctMTAtMTAtMTBoLTY4di0zNDBoMTk2YzE2LjU0MiAwIDMwIDEzLjQ1OCAzMCAzMHYzMTBoLTY4Yy01LjUyMyAwLTEwIDQuNDc3LTEwIDEwczQuNDc3IDEwIDEwIDEwaDIxMS41NzJsNzYuODg1IDc2Ljg5YzMuOTA2IDMuOTA2IDEwLjIzOCAzLjkwNSAxNC4xNDMuMDAxbDI4LjI5LTI4LjI5YzMuOTAzLTMuOTAzIDMuOTA0LTEwLjIzOS0uMDAxLTE0LjE0M2wtMzUuNzI5LTM1LjcyOWMyMi4yMTQtNS4wODYgMzguODQtMjQuOTk2IDM4Ljg0LTQ4LjcyOXYtMzIwYzAtLjAyOC0uMDA0LS4wNTUtLjAwNC0uMDg0LS4wNDQtNS40NDgtNC41MTItOS45MTYtOS45OTYtOS45MTZ6bS0xNzUuMjkgMjg4Ljg0OC03LjA2OS0yMS4yMDcgMjEuMjA3IDcuMDY5LjAzMi4wMzJjLjAxNi4wMTYuMDIxLjAyMS4wMjEuMDIxLjcwOC43MDggOS45NDIgOS45NDEgMTA0LjM1MiAxMDQuMzQybC0xNC4xNDYgMTQuMTQ2Yy0xMS45OTgtMTEuOTk5LTM3LjcwMi0zNy43MDQtMTA0LjM5Ny0xMDQuNDAzem0xNjAuOTY3IDEzMi42ODMtMTQuMTQ2IDE0LjE0Ni0yOC4yODItMjguMjgzIDE0LjE0Ni0xNC4xNDZ6bS0yNS42NzctNjEuNTMxaC03LjU3OGwtMTAxLjEwMS0xMDEuMTAxYy0xLjA5OC0xLjA5OC0yLjQzNi0xLjkyNS0zLjkwOS0yLjQxNmwtNDIuNDItMTQuMTRjLTMuNTk1LTEuMTk3LTcuNTU2LS4yNjItMTAuMjMzIDIuNDE2LTIuNjc4IDIuNjc5LTMuNjEzIDYuNjQtMi40MTYgMTAuMjMzbDE0LjE0IDQyLjQyYy40OTEgMS40NzMgMS4zMTggMi44MTEgMi40MTYgMy45MDlsNTguNjc1IDU4LjY3OWgtMTAzLjU3NHYtMzEwYzAtMTYuNTQyIDEzLjQ1OC0zMCAzMC0zMGgxOTIuNzQzbC0yMi44NTcgODBoLTE2OS44ODZjLTUuNTIzIDAtMTAgNC40NzctMTAgMTBzNC40NzcgMTAgMTAgMTBoMTY0LjE3MWwtNS43MTQgMjBoLTE1OC40NTdjLTUuNTIzIDAtMTAgNC40NzctMTAgMTBzNC40NzcgMTAgMTAgMTBoMTUyLjc0M2wtNS43MTQgMjBoLTE0Ny4wMjljLTUuNTIzIDAtMTAgNC40NzctMTAgMTBzNC40NzcgMTAgMTAgMTBoMTQxLjMxNGwtMjQuOTI5IDg3LjI1M2MtLjg2MiAzLjAxNy0uMjU4IDYuMjY0IDEuNjMyIDguNzY5czQuODQ2IDMuOTc4IDcuOTgzIDMuOTc4aDM3Ljg2YzE2LjUyNyAwIDMyLjE0IDExLjk2OSAzMi4xNCAzMCAwIDE2LjU0Mi0xMy40NTggMzAtMzAgMzB6bTMwLTY5LjM4NGMtOC45NDMtNi42NTktMjAuMTcyLTEwLjYxNi0zMi4xNC0xMC42MTZoLTI0LjYwM2M0LjEzMS0xNC40NTkgNTQuMTY4LTE4OS41ODkgNTYuNzQzLTE5OC42eiIvPjxjaXJjbGUgY3g9IjEzMyIgY3k9IjM5Ni4wOSIgcj0iMTAiLz48L2c+PC9nPjwvc3ZnPg==',

			'rewrite'         => array( 'slug' => 'chapter' ),
			'supports'        => array( 'title', 'editor' ),
			'public'          => true,
			'capability_type' => array( $this->single, $this->plural ),
			'show_in_menu'  => 'edit.php?post_type=mbdsc_story',
		);

		$this->set_up_role_levels();

		add_filter( 'cmb2_override_mbdsc_chapter_story_meta_save', array( $this, 'save_chapter_order' ), 10, 4 );
		add_filter( 'cmb2_override__mbdsc_chapter_order_meta_save', array( $this, 'save_chapter_order' ), 10, 4 );
		add_filter( 'the_content', array( $this, 'content' ) );


		add_filter( 'posts_clauses', array( $this, 'order_by_story' ), 10, 2 );

	}

	public function register() {
		parent::register();
		remove_post_type_support( $this->post_type, 'comments' );
	}


	public function set_up_role_levels() {
		$plural = $this->plural;
		$single = $this->single;

		$this->reader_level = apply_filters( 'mbdsc_reader_level_capabilities', array(
			'read',
			'read_' . $plural,
			'read_' . $single
		) );

		$this->moderated_author_level = apply_filters( 'mbdsc_moderated_author_level_capabilities', array(
				'edit_' . $plural,
				'edit_' . $single,
				'delete_' . $plural,
				'delete_' . $single,
				'manage_' . $plural,
				'read',
				'read_' . $plural,
				'read_' . $single
			)
		);

		$this->author_level = apply_filters( 'mbdsc_author_level_capabilities', array(
				'publish_' . $plural,
				'publish_' . $single,
				'edit_published_' . $single,
				'edit_published_' . $plural,
				'delete_published_' . $single,
				'delete_published_' . $plural,
				'upload_files',
				'manage_' . $plural,

			)
		);

		$this->moderator_level = apply_filters( 'mbdsc_moderator_level_capabilities', array(
				'edit_others_' . $plural,
				'edit_others_' . $single,
				'delete_others_' . $plural,
				'delete_others_' . $single,
			)
		);

	}


	public function create_metaboxes() {

		$chapter_meta_box = new_cmb2_box( apply_filters( 'mbdsc_chapter_meta_box', array(
			'id'           => 'mbdsc_chapter_meta_box',
			'title'        => __( 'About the Chapter', 'mooberry-story-community' ),
			'object_types' => array( $this->post_type ), // Post type
			'context'      => 'side',
			'priority'     => 'high',
			'show_names'   => true, // Show field names on the left
		) ) );


		$chapter_meta_box->add_field( apply_filters( 'mbdsc_chapter_story_field', array(
			'name'       => __( 'Story', 'mooberry-story' ),
			'id'         => 'mbdsc_chapter_story',
			'type'       => 'select',
			'options_cb' => array( $this, 'get_books_for_user' ),
			//	'column'  => array( 'position' => 2 )

		) ) );

		/*$custom_fields = Mooberry_Story_Community_Custom_Fields_Settings::get_custom_chapter_fields();
		$this->add_custom_fields( $custom_fields, $chapter_meta_box );*/

	}

	function get_books_for_user( $field ) {
		global $post;

		$story_collection = new Mooberry_Story_Community_Story_Collection();
		$stories          = $story_collection->get_stories_by_user( get_post_field( 'post_author', $post->ID ) );
		$story_list       = array( '' => '' );
		foreach ( $stories as $story ) {
			$story_list[ $story->ID ] = $story->post_title;
		}

		return $story_list;
	}

	// Add the custom columns to the book post type:

	function set_custom_columns( $columns ) {
		unset( $columns['date'] );
		$columns['mbdsc_chapter_story']  = __( 'Story', 'mooberry-story-community' );
		$columns['_mbdsc_chapter_order'] = __( 'Order', 'mooberry-story-community' );
		$columns['author']               = __( 'Author', 'mooberry-story-community' );

		$columns['date'] = __( 'Published', 'mooberry-story-community' );

		return $columns;
	}

	function display_custom_columns( $column, $post_id ) {
		switch ( $column ) {

			case 'author' :

				echo get_post_field( 'post_author', $post_id );

				break;

			case '_mbdsc_chapter_order':
				echo get_post_meta( $post_id, $column, true );
				break;

			case 'mbdsc_chapter_story' :
				$story_id = get_post_meta( $post_id, $column, true );
				if ( $story_id != '' ) {
					echo get_the_title( $story_id );
				} else {
					echo '';
				}
				break;

		}
	}

	function sortable_columns( $columns ) {
		$columns['_mbdsc_chapter_order'] = '_mbdsc_chapter_order';
		$columns['mbdsc_chapter_story']  = 'mbdsc_chapter_story';

		return $columns;
	}

	function order_by_story( $clauses, $wp_query ) {
		if ( ! is_admin() || ! $wp_query->is_main_query() ) {
			return $clauses;
		}


		if ( in_array( $wp_query->get( 'orderby' ), array( 'mbdsc_chapter_story' ) ) ) {
			global $wpdb;
			$clauses['join'] .= " JOIN {$wpdb->postmeta} AS pm ON {$wpdb->posts}.ID=pm.post_id
JOIN {$wpdb->posts} as p2 on p2.ID=pm.meta_value";

			$clauses['where']   .= " and pm.meta_key='mbdsc_chapter_story'";
			$clauses['orderby'] = 'p2.post_title ';
			$clauses['orderby'] .= ( 'ASC' == strtoupper( $wp_query->get( 'order' ) ) ) ? 'ASC' : 'DESC';

		}

		return $clauses;
	}

	function columns_orderby( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( in_array( $query->get( 'orderby' ), array( '_mbdsc_chapter_order' ) ) ) {
			$query->set( 'orderby', 'meta_value' );
			//$query->set( 'meta_key', '_mbdsc_chapter_order' );
			$query->set( 'meta_type', 'numeric' );

			$query->set( 'meta_query', array(
				'relation' => 'OR',
				array(
					'key'     => '_mbdsc_chapter_order',
					'compare' => 'NOT EXISTS',
					'value'   => 'bug #23268'
				),
				array(
					'key'     => '_mbdsc_chapter_order',
					'compare' => 'EXISTS'
				)
			) );
		}

	}


	function save_chapter_order( $override, $a, $args, $field_obj ) {
		// if blank save to the end of the list
		$chapter_order = get_post_meta( $a['id'], '_mbdsc_chapter_order', true );
		if ( $chapter_order === '' ) {


			$story_id = $a['value'];

			$chapters = Mooberry_Story_Community_Chapter_Collection::get_chapters_by_story( $story_id );

			update_post_meta( $a['id'], '_mbdsc_chapter_order', count( $chapters ) + 1 );

		}

		return $override;

	}


	function content( $content ) {

		// this weeds out content in the sidebar and other odd places
		// thanks joeytwiddle for this update
		if ( ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		if ( ! is_single() ) {
			return $content;
		}

		//if it's a post that is part of a story, add next and prev links to top and bottom
		if ( get_post_type() == $this->post_type && is_main_query() && ! is_admin() ) {

			global $post;

			$story_text = $content;
			$content    = '';
			$content    .= '[mbdsc_toc_link]';
			$content    .= '[mbdsc_prev]';
			$content    .= '[mbdsc_next]';
			$content    .= '<br style="clear:both;">';

	/*	$custom_fields = Mooberry_Story_Community_Custom_Fields_Settings::get_custom_chapter_fields();
			foreach ( $custom_fields as $custom_field ) {
			    $content .= '[mbdsc_custom_field_chapter chapter="' . $post->post_name . '" field="' . $custom_field->unique_id  . '"]';
            }*/

			$content    .= '<div class="mbdsc_chapter_text">' . $story_text . '</div>';
			$content    .= '[mbdsc_toc_link]';
			$content    .= '[mbdsc_prev]';
			$content    .= '[mbdsc_next]';
			$content    .= '<br style="clear:both;">';
		}


		return apply_filters( 'mbdsc_chapter_content', $content );

	}
}
