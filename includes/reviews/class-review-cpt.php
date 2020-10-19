<?php


class Mooberry_Story_Community_Review_CPT extends Mooberry_Story_Community_CPT {

	public function __construct() {
		parent::__construct( 'mbdsc_review' );

		$this->plural_name   = 'Reviews';
		$this->singular_name = 'Review';
		$this->single        = 'mbdsc_review';
		$this->plural        = 'mbdsc_reviews';

		$this->args = array(

			'supports'        => array( 'title', ),
			'public'          => true,
			'rewrite'         => array( 'slug' => 'review' ),
			'capability_type' => array( $this->single, $this->plural ),
			'show_ui'         => true,
			'show_in_menu'    => 'edit.php?post_type=mbdsc_story',
			'capabilities'    => array(
				'create_posts' => false,
				// Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
			),
			'map_meta_cap'    => true,
		);

		$this->set_up_role_levels();

		add_filter( 'the_content', array( $this, 'content' ) );

		add_action( 'wp_ajax_mbdsc_submit_review', array( $this, 'submit_review' ) );
		add_action( 'wp_ajax_nopriv_mbdsc_submit_review', array( $this, 'submit_review' ) );


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
			'read_' . $single,

		) );

		$this->moderated_author_level = apply_filters( 'mbdsc_moderated_author_level_capabilities', array(
				'read',
				'read_' . $plural,
				'read_' . $single
			)
		);

		$this->author_level = apply_filters( 'mbdsc_author_level_capabilities', array(

				'read',
				'read_' . $plural,
				'read_' . $single
			)
		);

		$this->moderator_level = apply_filters( 'mbdsc_moderator_level_capabilities', array(
				'edit_others_' . $plural,
				'edit_others_' . $single,
				'delete_others_' . $plural,
				'delete_others_' . $single,
				'edit_' . $plural,
				'edit_' . $single,
				'delete_' . $plural,
				'delete_' . $single,
				'manage_' . $plural,
				'publish_' . $plural,
				'publish_' . $single,
				'edit_published_' . $single,
				'edit_published_' . $plural,
				'delete_published_' . $single,
				'delete_published_' . $plural,
				'upload_files',


			)
		);

	}

	public function get_stories() {
		$stories = Mooberry_Story_Community_Story_Collection::get_all_published_stories();
		$story_list       = array( '' => '' );
		foreach ( $stories as $story ) {
			$story_list[ $story->id ] = $story->title;
		}

		return $story_list;
	}

	public function get_chapters() {
		$stories = Mooberry_Story_Community_Chapter_Collection::get_all_published_chapters();
		$story_list       = array( '' => '' );
		foreach ( $stories as $story ) {
			$story_list[ $story->id ] = $story->title;
		}

		return $story_list;
	}

	public function create_metaboxes() {

		$review_meta_box = new_cmb2_box( apply_filters( 'mbdsc_review_meta_box', array(
			'id'           => 'mbdsc_review_meta_box',
			'title'        => __( 'About the Review', 'mooberry-story-community' ),
			'object_types' => array( $this->post_type ), // Post type
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true, // Show field names on the left
		) ) );


		$review_meta_box->add_field( apply_filters( 'mbdsc_review_story_field', array(
			'name'       => __( 'Story', 'mooberry-story' ),
			'id'         => 'mbdsc_review_story',
			'type'       => 'select',
			'options_cb' => array( $this, 'get_stories' ),
			'column'     => array( 'position' => 2 ),
			'attributes' => array( 'disabled' => 'disabled' )

		) ) );

		$review_meta_box->add_field( apply_filters( 'mbdsc_review_chapter_field', array(
			'name'       => __( 'Chapter', 'mooberry-story' ),
			'id'         => 'mbdsc_review_chapter',
			'type'       => 'select',
			'options_cb' => array( $this, 'get_chapters' ),
			'column'     => array( 'position' => 3 ),
			'attributes' => array( 'disabled' => 'disabled' )

		) ) );


		$review_meta_box->add_field( apply_filters( 'mbdsc_review_reviewer_name_field', array(
			'name'       => __( 'Reviewer Name', 'mooberry-story' ),
			'id'         => 'mbdsc_reviewer_name',
			'type'       => 'text_medium',

		) ) );

		$review_meta_box->add_field( apply_filters( 'mbdsc_review_reviewer_email_field', array(
			'name'       => __( 'Reviewer Email', 'mooberry-story' ),
			'id'         => 'mbdsc_reviewer_email',
			'type'       => 'text_email',

		) ) );

		$review_meta_box->add_field( apply_filters( 'mbdsc_review_content_field', array(
			'name'       => __( 'Content', 'mooberry-story' ),
			'id'         => 'mbdsc_review_content',
			'type'       => 'textarea',

		) ) );

		/*$custom_fields = Mooberry_Story_Community_Custom_Fields_Settings::get_custom_review_fields();
		$this->add_custom_fields( $custom_fields, $review_meta_box );*/

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
		$content = '[mbdsc_review]';
		}

		return apply_filters( 'mbdsc_review_content', $content );

	}

	public function submit_review( ) {
			$nonce = $_POST['security'];


		// check to see if the submitted nonce matches with the
		// generated nonce we created earlier
		if (  ! wp_verify_nonce( $nonce, 'mbdsc_public_ajax_nonce' ) ) {
			die ();
		}

		$chapter_id = isset( $_POST['chapter'] ) ? intval($_POST['chapter']) : 0;
		$name = isset( $_POST['name'] ) ? $_POST['name'] : '';
		$email = isset( $_POST['email'] ) ? $_POST['email'] : '';
		$content = isset( $_POST['content'] ) ? $_POST['content'] : '';

		if ( $chapter_id != 0 && $email != '' && $content != '' & $name != '' ) {
			$chapter = new Mooberry_Story_Community_Chapter( $chapter_id );
			$new_review_id = wp_insert_post(array( 'post_title' => $chapter->title . ' by ' . $email, 'post_status'=>'publish', 'post_type'=>$this->post_type) );
			if ( $new_review_id != 0 ) {
				update_post_meta( $new_review_id, 'mbdsc_review_chapter', $chapter_id);
				update_post_meta( $new_review_id, 'mbdsc_review_story', $chapter->story_id);
				update_post_meta( $new_review_id, 'mbdsc_review_content', $content);
				update_post_meta( $new_review_id, 'mbdsc_reviewer_name', $name);
				update_post_meta( $new_review_id, 'mbdsc_reviewer_email', $email);

				// notify author
				/*Chapter 12 of your story Title has received a new review:
				Reviewer:
				Submitted: October 5, 2020 12:28 pm
				Review:
				*/
				$story = new Mooberry_Story_Community_Story( $chapter->story_id );
				$chapter_title = $chapter->title;
				$story_title = $story->title;
				 $author_email = $story->author->user->user_email;
				$message = do_shortcode("The chapter \"$chapter_title\" of your story $story_title has received a new review!
				\r\n <br> \r\n <br>[mbdsc_review review='$new_review_id']");


				$subject = "New review for $story_title!";
				if (strlen( $subject ) > 40 ) {
					$subject = substr( $subject, 0, 40 ) . '...';
				}

		wp_mail( $author_email, $subject, ( $message ), array(
			"Content-Type: text/html; charset=UTF-8",
		) );

			}
			echo json_encode( array('count'=> $chapter->review_count+1, 'review' => do_shortcode('[mbdsc_review review="' . $new_review_id . '"]<hr>') ) );

		}

		wp_die();
	}
}
