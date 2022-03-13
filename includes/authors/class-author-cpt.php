<?php


class Mooberry_Story_Community_Author_CPT  extends Mooberry_Story_Community_CPT {

	public function __construct() {

		parent::__construct( 'mbdsc_author' );

		$this->plural_name   = 'Authors';
		$this->singular_name = 'Author';
		$this->single        = 'mbdsc_author';
		$this->plural        = 'mbdsc_authors';

		$this->args = array(
			'public'          => true,
			'rewrite'         => array( 'slug' => 'story-author' ),
			'show_ui'         => false,
			'supports'        => array( 'title', 'author' ),
			'taxonomies'      => array_keys( $this->taxonomies ),
			'capability_type' => array( $this->single, $this->plural ),
			'has_archive'     => true,


		);

		$this->set_up_role_levels();
		add_filter( 'the_content', array( $this, 'content' ) );
		add_filter( 'the_title', array( $this, 'title' ), 10, 2 );
		add_action( 'pre_get_posts', array( $this, 'filter_author_archive') );
		add_filter( 'the_posts', array( $this, 'order_author_archive') );


	}

	// return display name
	public function title( $title, $post_id ) {
		if ( get_post_type( $post_id ) == $this->post_type ) {
			global $mbdsc_author_factory;
			$author = $mbdsc_author_factory->create_author( get_post_field('post_author', $post_id ) );
			//$stories = Mooberry_Story_Community_Factory_Generator::create_story_factory()->create_story_collection()::get_stories_by_user($author->user_id);
			global $mbdsc_story_factory;
			$stories = $mbdsc_story_factory->create_story_collection()::get_stories_by_user($author->user_id);
			$title = $author->display_name . ' (' . count($stories) . ' ' .  _n( 'story', 'stories', count($stories), 'mooberry-story-community' ) . ')';



		}
		return $title;
	}

	public function set_up_role_levels() {
		$single = $this->single;
		$plural = $this->plural;

		$this->reader_level = apply_filters( 'mbdsc_reader_level_capabilities', array(
			'read',
			'read_' . $plural,
			'read_' . $single,
			'read_others'
		) );

		$this->moderated_author_level = apply_filters( 'mbdsc_moderated_author_level_capabilities', array(
				'edit_' . $plural,
				'edit_' . $single,
				'delete_' . $plural,
				'delete_' . $single,
				'manage_' . $plural,
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

	public function create_metaboxes() {
		$cmb_user = new_cmb2_box( array(
			'id'               => 'mbdsc_user_metabox',
			'title'            => esc_html__( 'User Profile Metabox', 'cmb2' ),
			// Doesn't output for user boxes
			'object_types'     => array( 'user' ),
			// Tells CMB2 to use user_meta vs post_meta
			'show_names'       => true,
			'new_user_section' => 'add-new-user',
			// where form will show on new user page. 'add-existing-user' is only other valid option.
		) );


		$cmb_user->add_field(
			apply_filters( 'mbdsc_author_profile_image_field', array(
				'name'        => __( 'Profile Picture', 'mooberry-story-community' ),
				'id'          => 'mbdsc_author_profile_image',
				'type'        => 'file',
				'after_field' => __( 'Use this for your profile picture if you don\'t want to use Gravatar.', 'mooberry-story-community' ),
				'column'      => array( 'position' => 2, 'name' => '' ),
				'allow'       => array( 'attachment' ) // limit to just attachments with array( 'attachment' )
			) )
		);

	}

	public function content( $content ) {

		// this weeds out content in the sidebar and other odd places
		// thanks joeytwiddle for this update
		if ( ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		if ( get_post_type() !== $this->post_type ) {
			return $content;
		}

	/*	if ( is_archive() ) {
			return $this->get_archive_content( $content );
		}
*/

		// if it's a story page, show the TOC
		if ( is_single() && ! is_admin() ) {
			global $post;
			//$author = get_user_by('id', $post->post_author);
			$user = get_user_by( 'id', $post->post_author );

			$content = "[mbdsc_author_pic class='mbdsc_author_pic_profile_page' author='$post->post_author'][mbdsc_author_bio author='$post->post_author']";
			$content .= '<h2 class="mbdsc_author_stories">' . $user->display_name . '\'s Stories</h2>';
			$content .= "[mbdsc_author_stories author='$post->post_author']";
		}

		return apply_filters( 'mbdsc_author_archive_content', $content );
	}

	public function filter_author_archive( $query ) {
		if ( !is_admin() && $query->is_main_query() ) {
			if ( $query->is_post_type_archive && $query->query['post_type'] == $this->post_type ) {
				// get authors with at least one story
				$where = get_posts_by_author_sql('mbdsc_story', true, null, true);
				global $wpdb;
				$sql = "select post_author from $wpdb->posts $where group by post_author";
				$authors = $wpdb->get_col($sql);

				$query->set('author__in', $authors);
			}
		}
	}

	public function order_author_archive( $posts ) {
		global $wp_query;
		if ( !is_admin() && $wp_query->is_main_query() ) {
			if ( $wp_query->is_post_type_archive && $wp_query->query['post_type'] == $this->post_type ) {
				usort( $posts, function ( $a, $b ) {
					$user_a = new WP_User($a->post_author);
					$user_b = new WP_User($b->post_author);
					return strnatcasecmp($user_a->display_name, $user_b->display_name);
				});
			}
		}
		return $posts;
	}
}
