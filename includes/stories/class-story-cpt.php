<?php


class Mooberry_Story_Community_Story_CPT extends Mooberry_Story_Community_CPT {

	public function __construct() {
		parent::__construct( 'mbdsc_story' );

		$this->plural_name   = 'Stories';
		$this->singular_name = 'Story';
		$this->single        = 'mbdsc_story';
		$this->plural        = 'mbdsc_stories';

		// get taxonomies
		$custom_taxonomies = Mooberry_Story_Community_Custom_Taxonomies_Settings::get_taxonomies();
		foreach ( $custom_taxonomies as $custom_taxonomy ) {

			$new_taxonomy = new Mooberry_Story_Community_Taxonomy( $custom_taxonomy->taxonomy, $custom_taxonomy->slug, $this->post_type, $custom_taxonomy->singular_name, $custom_taxonomy->plural_name, array(
				//	'meta_box_cb'  => 'post_categories_meta_box',
				'capabilities' => array(
					'manage_terms' => 'add_' . $custom_taxonomy->taxonomy . '_terms', //'manage_categories',
					'edit_terms'   => 'edit_' . $custom_taxonomy->taxonomy . '_terms', //'manage_categories',
					'delete_terms' => 'delete_' . $custom_taxonomy->taxonomy . '_terms',
					'assign_terms' => 'assign_' . $custom_taxonomy->taxonomy . '_terms',
				),
				'hierarchical' => $custom_taxonomy->is_hierarchical,
				'rewrite'      => array(
					'slug'         => strtolower( sanitize_text_field( $this->plural_name ) ) . '/' . $custom_taxonomy->slug,
					'with_front'   => false,
					'hierarchical' => $custom_taxonomy->is_hierarchical,
				),

			) );
			$this->add_taxonomy( $new_taxonomy );
		}

		$this->args = array(
			'public'          => true,
			'rewrite'         => array( 'slug' => 'story' ),
			'menu_icon'       => 'data:image/svg+xml;base64,PHN2ZyBpZD0iQ2FwYV8xIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA1MTIgNTEyIiBoZWlnaHQ9IjUxMiIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHdpZHRoPSI1MTIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGc+PGc+PHBhdGggZD0ibTI5NiA0MjJoLTEyMGMwLTguMzk1IDAtMzk4LjM1NiAwLTQxMmgxMjB6IiBmaWxsPSIjZDhlYzg0Ii8+PHBhdGggZD0ibTUwMiA0MjJ2NDBjLTc1LjEwNyAwLTQ3Ni4xNDUgMC00OTIgMHYtNDB6IiBmaWxsPSIjODY5MGE2Ii8+PHBhdGggZD0ibTM1NiAzMTBoMTIwdjExMmgtMTIweiIgZmlsbD0iI2ZmOTI1NiIvPjxwYXRoIGQ9Im00NzYgMTcwdjE0MGgtMTIwdi0xNDB6IiBmaWxsPSIjZWVmYWZmIi8+PHBhdGggZD0ibTM1NiAxMzBoMTIwdjQwaC0xMjB6IiBmaWxsPSIjZmY5MjU2Ii8+PGVsbGlwc2UgY3g9IjQxNiIgY3k9IjI0MCIgZmlsbD0iI2ZmOTI1NiIgcng9IjIwIiByeT0iMzAiLz48cGF0aCBkPSJtMzU2IDQyMmgtNjB2LTMzMmg2MHoiIGZpbGw9IiNmZmVlODAiLz48Y2lyY2xlIGN4PSIyMzYiIGN5PSI3MCIgZmlsbD0iI2VlZmFmZiIgcj0iMjAiLz48Y2lyY2xlIGN4PSIyMzYiIGN5PSIzNjIiIGZpbGw9IiNlZWZhZmYiIHI9IjIwIi8+PHBhdGggZD0ibTE3NS4zNiAxMTcuMzktMTUuNTMgNTcuOTYtNTcuOTUtMTUuNTMgMTUuNTMtNTcuOTZjMi44NS0xMC42NyAxMy44Mi0xNyAyNC40OS0xNC4xNGwxOS4zMiA1LjE4YzEwLjc3OCAyLjg5NSAxNi45ODIgMTMuOTU3IDE0LjE0IDI0LjQ5eiIgZmlsbD0iI2E0Y2FmZiIvPjxwYXRoIGQ9Im05My4wOTMgMTY2LjU2M2g1OS45OTV2NTkuOTk1aC01OS45OTV6IiBmaWxsPSIjZWVmYWZmIiB0cmFuc2Zvcm09Im1hdHJpeCguMjU5IC0uOTY2IC45NjYgLjI1OSAtOTguNjMzIDI2NC41NzQpIi8+PHBhdGggZD0ibTE0NC4zIDIzMy4zLTQ2LjU4IDE3My44N2MtMi44MzQgMTAuNTU2LTEzLjY3NyAxNy4wMTgtMjQuNSAxNC4xNGwtMTkuMzItNS4xN2MtMTAuNjctMi44Ni0xNy0xMy44My0xNC4xNC0yNC41bDQ2LjU5LTE3My44N3oiIGZpbGw9IiNhNGNhZmYiLz48L2c+PGc+PHBhdGggZD0ibTIzNiAxMDBjMTYuNTQyIDAgMzAtMTMuNDU4IDMwLTMwcy0xMy40NTgtMzAtMzAtMzAtMzAgMTMuNDU4LTMwIDMwIDEzLjQ1OCAzMCAzMCAzMHptMC00MGM1LjUxNCAwIDEwIDQuNDg2IDEwIDEwcy00LjQ4NiAxMC0xMCAxMC0xMC00LjQ4Ni0xMC0xMCA0LjQ4Ni0xMCAxMC0xMHoiLz48cGF0aCBkPSJtMjM2IDM5MmMxNi41NDIgMCAzMC0xMy40NTggMzAtMzBzLTEzLjQ1OC0zMC0zMC0zMC0zMCAxMy40NTgtMzAgMzAgMTMuNDU4IDMwIDMwIDMwem0wLTQwYzUuNTE0IDAgMTAgNC40ODYgMTAgMTBzLTQuNDg2IDEwLTEwIDEwLTEwLTQuNDg2LTEwLTEwIDQuNDg2LTEwIDEwLTEweiIvPjxwYXRoIGQ9Im0yMjYgMTMwdjE3MmMwIDUuNTIzIDQuNDc3IDEwIDEwIDEwczEwLTQuNDc3IDEwLTEwdi0xNzJjMC01LjUyMy00LjQ3Ny0xMC0xMC0xMHMtMTAgNC40NzctMTAgMTB6Ii8+PHBhdGggZD0ibTQxNiAyODBjMTYuODIyIDAgMzAtMTcuNTcgMzAtNDBzLTEzLjE3OC00MC0zMC00MC0zMCAxNy41Ny0zMCA0MCAxMy4xNzggNDAgMzAgNDB6bTAtNjBjNC4wNzcgMCAxMCA3Ljc5MSAxMCAyMHMtNS45MjMgMjAtMTAgMjAtMTAtNy43OTEtMTAtMjAgNS45MjMtMjAgMTAtMjB6Ii8+PGNpcmNsZSBjeD0iMjU2IiBjeT0iNDYyIiByPSIxMCIvPjxwYXRoIGQ9Im01MDIgNDEyaC0xNnYtMjgyYzAtNS41MjMtNC40NzctMTAtMTAtMTBoLTExMHYtMzBjMC01LjUyMy00LjQ3Ny0xMC0xMC0xMGgtNTB2LTcwYzAtNS41MjMtNC40NzctMTAtMTAtMTBoLTEyMGMtNS41MjMgMC0xMCA0LjQ3Ny0xMCAxMHY3My45MjNjLS43MTctLjI1Mi0xLjQ0Ny0uNDgyLTIuMTktLjY4MmwtMTkuMzItNS4xOGMtMTYuMDAzLTQuMjkyLTMyLjQ1OSA1LjE4Ni0zNi43MzggMjEuMjEtMS4yMiA0LjU1My03Ny4yMTEgMjg4LjE0MS03Ny42NSAyODkuNzgtMi4xNjIgOC4wNjYtLjgwNCAxNi4yNTkgMy4xMjMgMjIuOTQ5aC0yMy4yMjVjLTUuNTIzIDAtMTAgNC40NzctMTAgMTB2NDBjMCA1LjUyMyA0LjQ3NyAxMCAxMCAxMGg2NmM1LjUxNCAwIDEwIDQuNDg2IDEwIDEwcy00LjQ4NiAxMC0xMCAxMGMtNS41MjMgMC0xMCA0LjQ3Ny0xMCAxMHM0LjQ3NyAxMCAxMCAxMGMxNi41NDIgMCAzMC0xMy40NTggMzAtMzAgMC0zLjUwNi0uNjEtNi44Ny0xLjcyLTEwaDEwNi43MmM1LjUyMyAwIDEwLTQuNDc3IDEwLTEwcy00LjQ3Ny0xMC0xMC0xMGgtMTkxdi0yMGg0NzJ2MjBoLTE5MWMtNS41MjMgMC0xMCA0LjQ3Ny0xMCAxMHM0LjQ3NyAxMCAxMCA5Ljk5aDEwNi43MmMtMS4xMSAzLjE0LTEuNzIgNi41MDQtMS43MiAxMC4wMSAwIDE2LjU0MiAxMy40NTggMzAgMzAgMzAgNS41MjMgMCAxMC00LjQ3NyAxMC0xMHMtNC40NzctMTAtMTAtMTBjLTUuNTE0IDAtMTAtNC40ODYtMTAtMTBzNC40ODYtMTAgMTAtMTBoNjZjNS41MjMgMCAxMC00LjQ3NyAxMC0xMHYtNDBjMC01LjUyMy00LjQ3Ny0xMC0xMC0xMHptLTM5My4wNS0yMzkuOTMzIDM4LjYzNCAxMC4zNTQtMTAuMzUxIDM4LjYzMi0zOC42MzUtMTAuMzU0em0tMzMuMTQ1IDIzOS41ODMtMTkuMzE2LTUuMTY5Yy01LjMyNy0xLjQyOC04LjQ5OS02LjkyNC03LjA3LTEyLjI1Mmw0NC4wMDItMTY0LjIxMSAzOC42MzYgMTAuMzU0LTQzLjk5NSAxNjQuMjA1Yy0xLjM5NSA1LjE5OS02LjgwOCA4LjUyMy0xMi4yNTcgNy4wNzN6bTkwLjE5NS4zNWgtNTkuMzI1Yy4yNi0uNzM0LjQ5OS0xLjQ4LjcwNC0yLjI0Mmw1OC42MjEtMjE4Ljc5M3ptLS4yOTktMjk3LjE5OC0xMi45NDEgNDguMy0zOC42MzMtMTAuMzU0IDEyLjk0NS00OC4zMDljMS40MjEtNS4zMiA2Ljg4OS04LjQ5NCAxMi4yMzktNy4wNjFsMTkuMzE2IDUuMTc5YzUuNDQzIDEuNDYzIDguNDc4IDcuMDQxIDcuMDc0IDEyLjI0NXptMTIwLjI5OSAyOTcuMTk4aC0xMDBjMC00MC41NzUgMC0zOTEuNjc0IDAtMzkyaDEwMHptNjAgMGgtNDB2LTMxMmg0MHptMTIwIDBoLTEwMHYtOTJoMTAwem0wLTExMmgtMTAwdi0xMjBoMTAwem0wLTE0MGgtMTAwdi0yMGgxMDB6Ii8+PC9nPjwvZz48L3N2Zz4=',
			'supports'        => array( 'title', 'author' ),
			'taxonomies'      => array_keys( $this->taxonomies ),
			'capability_type' => array( $this->single, $this->plural ),
			'has_archive'     => true,


		);


		$this->set_up_role_levels();
		add_action( 'cmb2_init', array( $this, 'create_metaboxes' ) );
		add_action( 'cmb2_init', array( $this, 'create_taxonomy_metaboxes' ) );
		add_action( 'cmb2_admin_init', array( $this, 'create_admin_taxonomy_metaboxes' ) );
		add_action( 'cmb2_override_meta_save', array( $this, 'override_new_taxonomy_term_saving' ), 10, 4 );
		add_action( 'add_meta_boxes_mbdsc_story', array( $this, 'mbds_add_posts_meta_box' ) );
		add_action( 'wp_ajax_save_chapters_order', array( $this, 'save_chapters_order_admin' ) );
		add_action( 'wp_ajax_nopriv_save_chapters_order', array( $this, 'save_chapters_order_public' ) );
		add_action( 'wp_ajax_save_chapter', array( $this, 'save_chapter' ) );
		add_action( 'wp_ajax_get_chapter', array( $this, 'get_chapter' ) );
		add_action( 'wp_ajax_mbdsc_delete_chapter', array( $this, 'delete_chapter' ) );
		add_action( 'wp_ajax_mbdsc_delete_story', array( $this, 'delete_story' ) );
		add_action( 'wp_ajax_nopriv_mbdsc_delete_story', array( $this, 'delete_story' ) );
		add_filter( 'the_content', array( $this, 'content' ) );


	}

	public function register() {
		parent::register();
		remove_post_type_support( $this->post_type, 'comments' );
	}


	function mbds_add_posts_meta_box() {

		// Start with an underscore to hide fields from custom fields list

		add_meta_box( '_mbds_posts_meta_box', __( 'Chapters', 'mooberry-story' ), array(
			$this,
			'mbds_posts_meta_box',
		), 'mbdsc_story', 'normal',
			'default' );
	}

	function mbds_posts_meta_box() {
		global $post;
		echo '<p>' . __( 'Drag and drop the items to reorder them.', 'mooberry-story' ) . '</p>';
		echo '	<ol id="mbdsc_chapter_list">';


		$chapters = Mooberry_Story_Community_Chapter_Collection::get_chapters_by_story( $post->ID );
		foreach ( $chapters as $chapter ) {
			echo '<img class="mbdsc_chapter_delete_icon" id="mbdsc_chapter_delete_icon_' . $chapter->id . '" src="' . MOOBERRY_STORY_COMMUNITY_PLUGIN_URL . 'assets/delete.png" data-chapter_id="' . $chapter->id . '"/><li id="mbdsc_chapter_' . $chapter->id . '" class="ui-state-default"><span class="ui-icon"></span> <div class="mbdsc_chapter_title" id="mbdsc_chapter_title_' . $chapter->id . '" >' . $chapter->title . '</div><a href="' . $chapter->link . '" target="_new"><img class="mbdsc_chapter_preview_icon" src="' . MOOBERRY_STORY_COMMUNITY_PLUGIN_URL . 'assets/new_window_icon.png"/></a><img class="mbdsc_chapter_list_edit" id="mbdbsc_chapter_edit_' . $chapter->id . '" src="' . MOOBERRY_STORY_COMMUNITY_PLUGIN_URL . 'assets/edit.png" data-chapter_id="' . $chapter->id . '"/>
</li>';
		}

		echo '</ol>';

		ob_start();
		wp_editor( htmlspecialchars( '' ), 'mbdsc_chapter_text', array(
			'textarea_name' => 'mbdsc_chapter_text',
			'media_buttons' => false,
			'editor_height' => 200,
		) );
		$editor = ob_get_clean();

		echo '	<div id="mbdsc_chapter_dialog" title="Edit Chapter" style="display:none" >
	<div id="mbdsc_chapter_form_error" style="display:none;">Sorry, an error has occurred. Please try again later.</div>
	<div id="mbdsc_chapter_form">
  <p class="validateTips">All form fields are required.</p>


      <label for="title">Title</label>
      <input type="text" name="mbdsc_chapter_title" id="mbdsc_chapter_title" value="" class="text ui-widget-content ui-corner-all" style="width:100%">
      ' . $editor . '<input type="hidden" id="mbdsc_edit_chapter_id">

      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
  </div>
  <div id="mbdsc_chapter_form_loading" style="display:none"><img src="' . MOOBERRY_STORY_COMMUNITY_PLUGIN_URL . '/assets/ajax-loader.gif"/></div>
</div> ';
		echo '<button class="button-secondary" id="mbdsc_add_chapter">Add Chapter</button>';
	}

	protected function delete_post( $post_id, $nonce ) {

		// check to see if the submitted nonce matches with the
		// generated nonce we created earlier
		if ( ! wp_verify_nonce( $_POST['security'], $nonce ) ) {
			echo 'ERROR';
			die ();
		}


		$result = wp_trash_post( $post_id );
		if ( ! isset( $result ) || $result === false ) {
			echo false;
		} else {
			echo true;
		}
		wp_die();
	}

	function delete_story() {
		$this->delete_post( wp_kses_post( $_POST['story'] ), 'mbdsc_story_cpt_ajax_nonce' );
	}

	function delete_chapter() {
		$this->delete_post( wp_kses_post( $_POST['chapter'] ), 'mbdsc_story_cpt_ajax_nonce' );
	}

	function get_chapter() {
		$nonce = $_POST['security'];


		// check to see if the submitted nonce matches with the
		// generated nonce we created earlier
		if ( ! wp_verify_nonce( $nonce, 'mbdsc_story_cpt_ajax_nonce' ) ) {
			echo 'ERROR';
			die ();
		}


		$chapter_id = wp_kses_post( $_POST['chapter'] );
		$chapter    = new Mooberry_Story_Community_Chapter( $chapter_id );
		echo json_encode( array( 'title' => $chapter->title, 'body' => $chapter->body ) );
		wp_die();
	}


	function save_chapter() {
		$nonce = $_POST['security'];


		// check to see if the submitted nonce matches with the
		// generated nonce we created earlier
		if ( ! wp_verify_nonce( $nonce, 'mbdsc_story_cpt_ajax_nonce' ) ) {
			die ();
		}


		$story_id = intval( $_POST['story'] );
		$title    = sanitize_text_field( $_POST['title'] );
		$chapter  = wp_kses_post( $_POST['chapter'] );

		$chapter_id = isset( $_POST['chapter_id'] ) ? intval( $_POST['chapter_id'] ) : 0;

		$new_chapter_id = 0;
		$link           = '';
		if ( $chapter_id == 0 ) {

			$new_chapter_id = wp_insert_post( array(
				'post_content' => $chapter,
				'post_title'   => $title,
				'post_type'    => 'mbdsc_chapter',
				'post_status'  => 'publish',
				'post_author'  => get_post_field( 'post_author', $story_id ),
			) );
			update_post_meta( $new_chapter_id, 'mbdsc_chapter_story', $story_id );
			$chapters = Mooberry_Story_Community_Chapter_Collection::get_chapters_by_story( $story_id );

			update_post_meta( $new_chapter_id, '_mbdsc_chapter_order', count( $chapters ) + 1 );
			$link = get_permalink( $new_chapter_id );

		} else {
			wp_update_post( array(
				'ID'           => $chapter_id,
				'post_content' => $chapter,
				'post_title'   => $title,
				'post_type'    => 'mbdsc_chapter',
				'post_status'  => 'publish',

			) );
		}


		echo json_encode( array( 'new_chapter_id' => $new_chapter_id, 'new_chapter_url' => $link ) );
		wp_die();
	}

	function save_chapters_order_admin() {
		$this->save_chapters_order( 'mbdsc_story_cpt_ajax_nonce',  'mbdsc_chapter' );
	}

	function save_chapters_order_public() {
		$this->save_chapters_order( 'mbdsc_story_cpt_ajax_nonce', 'mbdsc_chapter' );
	}

	function save_chapters_order( $nonce,  $key) {
		// check to see if the submitted nonce matches with the
		// generated nonce we created earlier
		if ( ! wp_verify_nonce( $_POST['security'], $nonce ) ) {
			die ();
		}

		// v1.2.1 -- add check for posts to be blank
		if ( isset( $_POST['chapters'] ) && $_POST['chapters'] != '' ) {

			parse_str( $_POST['chapters'], $chapters );
			if ( isset( $chapters[$key] ) ) {

				$x = 1;
				foreach ( $chapters[$key] as $chapter_id ) {
					update_post_meta( $chapter_id, '_mbdsc_chapter_order', $x );
					$x ++;
				}
			}

		}

	}

	function set_custom_columns( $columns ) {
		$author = $columns['author'];
		$date   = $columns['date'];
		unset ( $columns['author'] );
		unset ( $columns['date'] );
		$columns['chapter_count'] = __( 'Number of Chapters', 'mooberry-story-community' );
		$columns['complete']      = __( 'Story is Complete?', 'mooberry-story-community' );
		$columns['author']        = $author;
		$columns['date']          = $date;

		return $columns;

	}

	function display_custom_columns( $column, $post_id ) {
		parent::display_custom_columns( $column, $post_id );

		$story = new Mooberry_Story_Community_Story( $post_id );
		if ( $column == 'chapter_count' ) {
			echo Mooberry_Story_Community_Chapter_Collection::get_chapter_count_for_story( $post_id );
		}
		if ( $column == 'complete' ) {

			//$complete = get_post_meta( $post_id, 'mbdsc_story_complete', true);
			echo $story->is_complete ? __( 'Yes', 'mooberry-story-community' ) : __( 'No', 'mooberry-story-community' );
		}
	}

	function content( $content ) {

		// this weeds out content in the sidebar and other odd places
		// thanks joeytwiddle for this update
		if ( ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		if ( get_post_type() !== $this->post_type ) {
			return $content;
		}

		if ( is_archive() ) {
			return $this->get_archive_content( $content );
		}


		// if it's a story page, show the TOC
		if ( is_single() && ! is_admin() ) {
			global $post;

			$slug = $post->post_name;

			$content = '[mbdsc_author story="' . $slug . '" byline="yes"]';

			$content .= '[mbdsc_cover story="' . $slug . '"]';

			$content .= '[mbdsc_summary story="' . $slug . '"]';

			$content .= '[mbdsc_story_word_count story="' . $slug . '"]';

			$content .= '[mbdsc_complete story="' . $slug . '"]';

			$custom_fields = Mooberry_Story_Community_Custom_Fields_Settings::get_custom_story_fields();
			foreach ( $custom_fields as $custom_field ) {
				$content .= '[mbdsc_custom_field_story story="' . $slug . '" field="' . $custom_field->unique_id . '"]';
			}

			$taxonomies = Mooberry_Story_Community_Custom_Taxonomies_Settings::get_taxonomies();
			foreach ( $taxonomies as $taxonomy ) {
				if ( $taxonomy->display_toc ) {
					$content .= '[mbdsc_taxonomy_field story="' . $slug . '" tax="' . $taxonomy->slug . '"]';
				}
			}


			$content .= '[mbdsc_toc story="' . $slug . '" word_count="yes" review_count="yes"]';
		}

		return apply_filters( 'mbdsc_story_content', $content );

	}

	protected function get_archive_content( $content ) {
		global $post;
		$slug = $post->post_name;

		$content = '[mbdsc_author byline="yes" story="' . $slug . '"]';

		$content .= '[mbdsc_summary story="' . $slug . '"]';

		$content .= '[mbdsc_cover link="yes" story="' . $slug . '"]';

		return $content;
	}

	public function create_metaboxes() {


		$story_meta_box = new_cmb2_box( apply_filters( 'mbdsc_story_meta_box', array(
			'id'           => 'mbdsc_story_meta_box',
			'title'        => __( 'About the Story', 'mooberry-story-community' ),
			'object_types' => array( $this->post_type ), // Post type
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true, // Show field names on the left
		) ) );

		$story_meta_box->add_field( apply_filters( 'mbdsc_story_complete_field', array(
			'name' => __( 'Story Is Complete?', 'mooberry-story-community' ),
			'id'   => 'mbdsc_story_complete',
			'type' => 'checkbox',
		) ) );


		$story_meta_box->add_field( apply_filters( 'mbdsc_story_summary_field', array(
			'name'    => __( 'Story Summary', 'mooberry-story-community' ),
			'id'      => 'mbdsc_story_summary',
			'type'    => 'wysiwyg',
			'options' => array(
				'wpautop'       => true,
				// use wpautop?
				'media_buttons' => false,
				// show insert/upload button(s)
				'textarea_rows' => 5,
				'dfw'           => false,
				// replace the default fullscreen with DFW (needs specific css)
				'tinymce'       => true,
				// load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
				'teeny'         => true,
				'quicktags'     => true
				// load Quicktags, can be used to pass settings directly to Quicktags using an array()
			),
		) ) );

		$story_meta_box->add_field( apply_filters( 'mbdsc_story_chapter_titles_field', array(
			'name'    => __( 'How are Chapters Titled?', 'mooberry-story-community' ),
			'id'      => 'mbdsc_story_chapter_titles',
			'type'    => 'radio',
			'options' => array(
				'auto'   => __( 'Automatically number chapters: Chapter 1, Chapter 2, etc.', 'mooberry-story-community' ),
				'custom' => __( 'Allow me to set chapter titles', 'mooberry-story-community' ),
			),
		) ) );


		$custom_fields = Mooberry_Story_Community_Custom_Fields_Settings::get_custom_story_fields();
		$this->add_custom_fields( $custom_fields, $story_meta_box );


		/*$chapters_metabox =  new_cmb2_box( apply_filters('mbdsc_story_chapters_meta_box', array(
			'id'            => 'mbdsc_story_chatpers_meta_box',
			'title'         => __( 'Chapters', 'mooberry-story-community' ),
			'object_types'  => array( $this->post_type ), // Post type
			 'context'    => 'normal',
			 'priority'   => 'high',
			 'show_names' => true, // Show field names on the left
		) ) );*/


		$cover_image_meta_box = new_cmb2_box( apply_filters( 'mbdsc_story_cover_image_meta_box', array(
			'id'           => 'mbdsc_cover_image',
			'title'        => __( 'Story Cover', 'mooberry-story-community' ),
			'object_types' => array( $this->post_type ), // Post type
			'context'      => 'side',
			'priority'     => 'default',
			'show_names'   => ! is_admin(), // Show field names on the left
		) ) );

		$cover_image_meta_box->add_field( apply_filters( 'mbdsc_story_cover_image_field', array(
			'name'   => __( 'Story Cover', 'mooberry-story-community' ),
			'id'     => 'mbdsc_story_cover',
			'type'   => 'file',
			'column' => array( 'position' => 2, 'name' => '' ),
			'allow'  => array( 'attachment' ) // limit to just attachments with array( 'attachment' )
		) ) );


	}

	public function create_admin_taxonomy_metaboxes() {
		$custom_taxonomies = Mooberry_Story_Community_Custom_Taxonomies_Settings::get_taxonomies();
		foreach ( $custom_taxonomies as $taxonomy ) {
			if ( ! current_user_can( 'add_' . $taxonomy->taxonomy . '_terms' ) ) {
				$this->create_taxonomy_metabox( $taxonomy, 'admin' );
			}
		}
	}

	public function create_taxonomy_metabox( $taxonomy, $context ) {
		$metabox = new_cmb2_box( array(
				'id'           => $taxonomy->taxonomy . '_taxonomy_' . $context . '_metabox',
				'title'        => $taxonomy->singular_name,
				'object_types' => array( $this->post_type, ),
				'context'      => 'side',
				'priority'     => 'default',
				'show_names'   => ! is_admin(),
			)
		);
		if ( $taxonomy->allow_multiple ) {
			$type = $taxonomy->is_hierarchical ? 'taxonomy_multicheck_hierarchical' : 'taxonomy_multicheck';
		} else {
			$type = $taxonomy->is_hierarchical ? 'taxonomy_select_hierarchical' : 'taxonomy_select';
		}
		$args = array(
			'id'             => $taxonomy->taxonomy,
			'type'           => $type,
			'name'           => $taxonomy->singular_name,
			'taxonomy'       => $taxonomy->taxonomy,
			'remove_default' => true,
			'text'           => array(
				'no_terms_text' => 'Sorry, no ' . strtolower( $taxonomy->plural_name ) . ' could be found.'
				// Change default text. Default: "No terms"
			),
			// Optionally override the args sent to the WordPress get_terms function.
			'query_args'     => array(
				'orderby' => 'slug',
				// 'hide_empty' => true,
			),

		);
		if ( $taxonomy->is_required ) {
			$args['attributes'] = array( 'required' => 'required' );
		}

		$metabox->add_field( $args );

		if ( $context == 'public' && current_user_can( 'add_' . $taxonomy->taxonomy . '_terms' ) ) {
			$parent_dropdown = '';
			if ( $taxonomy->is_hierarchical ) {


				$parent_dropdown = '<div class="cmb-row cmb-type-select cmb2-id-' . str_replace('_', '-', $taxonomy->taxonomy) . '-new-item-parent table-layout mbdsc_taxonomy_new_item_parent">
<div class="cmb-th">
<label for="' . $taxonomy->taxonomy . '_new_item_parent">Parent</label>
</div>
	<div class="cmb-td">';
				$parent_dropdown .= wp_dropdown_categories( array(
					'show_option_none'  => ' ',
					'option_none_value' => '0',
					'taxonomy'          => $taxonomy->taxonomy,
					'echo'              => false,
					'hierarchical'      => true,
					'hide_empty'        => false,
					'id'                => $taxonomy->taxonomy . '_new_item_parent',
					'name'              => $taxonomy->taxonomy . '_new_item_parent',
					//'class'             => 'mbdsc_taxonomy_new_item_parent',
				) );
				$parent_dropdown .= '</div>
</div>';


				/*	$metabox->add_field( array(
					'id'    =>  $taxonomy->taxonomy . '_new_item_parent',
					'taxonomy'=>$taxonomy->taxonomy,
					'classes'   => 'mbdsc_taxonomy_new_item_parent',
					'type'  =>  'taxonomy_select_hierarchical',
					'name'  =>  __('Parent ' . $taxonomy->singular_name, 'mooberry-story-community'),
					));*/
			}

			$metabox->add_field( array(
				'id'         => $taxonomy->taxonomy . '_new_item',
				'type'       => 'text',
				'classes'    => 'mbdsc_taxonomy_new_item',
				'name'       => __( 'New ' . $taxonomy->singular_name, 'mooberry-story-community' ),
				'before_row' => '<button type="button" class="mbdsc_taxonomy_add_new_button">Add New ' . $taxonomy->singular_name . '</button><button type="button" class="mbdsc_taxonomy_add_new_cancel_button">Cancel Adding New ' . $taxonomy->singular_name . '</button>',
				'after_row'  => $parent_dropdown,
			) );


		}

	}

	public function create_taxonomy_metaboxes() {
		if ( ! is_admin() ) {
			$custom_taxonomies = Mooberry_Story_Community_Custom_Taxonomies_Settings::get_taxonomies();
			foreach ( $custom_taxonomies as $taxonomy ) {
				$this->create_taxonomy_metabox( $taxonomy, 'public' );

			}
		}
	}

	public function override_new_taxonomy_term_saving( $override, $args, $field_args, $field ) {

		$field_id = $args['field_id'];

		/*// totally override for parent dropdown
		if ( preg_match( '/(.*)_new_item_parent$/', $field_id, $matches ) ) {
			if ( taxonomy_exists( $matches[1] ) ) {
				return true;
			}
		}*/

		// save new terms (include parent if applicable)
		if ( preg_match( '/(.*)_new_item$/', $field_id, $matches ) ) {
			$taxonomy = $matches[1];
			if ( taxonomy_exists( $taxonomy ) ) {
				$override = true;
				// see if a parent is specified
				$term_args = array();
				if ( isset( $_POST[ $taxonomy . '_new_item_parent' ] ) ) {
					$parent_term = absint( $_POST[ $taxonomy . '_new_item_parent' ]);

						$term_args['parent'] = $parent_term;

				}
				// add new term
				$new_term = wp_insert_term( $args['value'], $taxonomy, $term_args );
				if ( ! is_wp_error( $new_term ) ) {
					// assign to object
					$taxonomy_object = Mooberry_Story_Community_Custom_Taxonomies_Settings::get_taxonomy($taxonomy);
					$append = $taxonomy_object ? $taxonomy_object->allow_multiple : true;
					wp_set_post_terms( $args['id'], array( $new_term['term_id'] ), $taxonomy, $append );
				}
			}
		}

		return $override;

	}

	public function set_up_role_levels() {
		$single = $this->single;
		$plural = $this->plural;

		$this->reader_level = apply_filters( 'mbdsc_reader_level_capabilities', array(
			'read',
			'read_' . $plural,
			'read_' . $single,
			'read_others',
		) );

		$this->moderated_author_level = apply_filters( 'mbdsc_moderated_author_level_capabilities', array(
				'edit_' . $plural,
				'edit_' . $single,
				'delete_' . $plural,
				'delete_' . $single,
				'manage_' . $plural,
				'read_' . $plural,
				'read_' . $single,
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
				'read',
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

}
