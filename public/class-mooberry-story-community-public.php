<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.mooberrydreams.com
 * @since      1.0.0
 *
 * @package    Mooberry_Story_Community
 * @subpackage Mooberry_Story_Community/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Mooberry_Story_Community
 * @subpackage Mooberry_Story_Community/public
 * @author     Mooberry Dreams <support@mooberrydreams.com>
 */
class Mooberry_Story_Community_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mooberry-story-community-public.css', array(), $this->version, 'all' );


		$wp_scripts = wp_scripts();
		wp_enqueue_style( $this->plugin_name . '-admin-ui-css',
			'http://ajax.googleapis.com/ajax/libs/jqueryui/' . $wp_scripts->registered['jquery-ui-core']->ver . '/themes/smoothness/jquery-ui.css',
			false,
			$this->version,
			false );


	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( "jquery-ui-tabs" );
		wp_enqueue_script( 'jquery-ui-sortable' );

		wp_enqueue_script( $this->plugin_name . '-common', MOOBERRY_STORY_COMMUNITY_PLUGIN_URL . 'includes/js/mooberry-story-community.js', array(
			'jquery',
		), $this->version, false );

		wp_enqueue_script( $this->plugin_name . '-public', plugin_dir_url( __FILE__ ) . 'js/mooberry-story-community-public.js', array(
			'jquery',
			'jquery-ui-tabs',
		), $this->version, false );

		wp_localize_script( $this->plugin_name . '-public', 'mbdsc_public_ajax_object', array(
			'ajax_url'              => admin_url( 'admin-ajax.php' ),
			'mbdsc_public_security' => wp_create_nonce( 'mbdsc_story_cpt_ajax_nonce' ),

		) );

	}

	public function add_new_user( $user_id ) {
		$wp_user = get_user_by( 'ID', $user_id );
		if ( user_can( $wp_user, 'edit_mbdsc_stories' ) ) {
			wp_insert_post( array(
				'post_type'   => 'mbdsc_author',
				'post_title'  => $wp_user->user_login,
				'post_status' => 'publish',
				'post_author' => $wp_user->ID,
			) );
		}
	}

	/**
 * Handles form submission on save. Redirects if save is successful, otherwise sets an error message as a cmb property
 *
 * @return void
 */
function handle_frontend_new_post_submission() {

	// If no form submission, bail
	if ( empty( $_POST ) || ! isset( $_POST['submit-cmb'], $_POST['object_id'] ) ) {
		return;
	}

	if ( $_POST['object_id'] != 'new_story' ) {
		return;
	}

	// Get CMB2 metabox object
		$metaboxes = array( 'mbdsc_story_meta_box', 'mbdsc_cover_image' );

			$custom_taxonomies = Mooberry_Story_Community_Custom_Taxonomies_Settings::get_taxonomies();
			foreach ( $custom_taxonomies as $taxonomy ) {



					$metaboxes[] = $taxonomy->taxonomy . '_taxonomy_public_metabox';

			}

$post_data = array();
			$sanitized_values = array();
				// Set our post data arguments
				$post_data['post_title'] = 'New Story';
				/*unset( $sanitized_values['submitted_post_title'] );
				$post_data['post_content'] = $sanitized_values['submitted_post_content'];
				unset( $sanitized_values['submitted_post_content'] );*/

				$post_data['post_type'] = 'mbdsc_story';
				$post_data['post_status'] = 'publish';
	// Create the new post
	$new_submission_id = wp_insert_post( $post_data, true );
	// If we hit a snag, update the user


			foreach ( $metaboxes as $metabox ) { // loop over config array
				$cmb = cmb2_get_metabox( $metabox, $_POST['object_id'] );
if ( is_wp_error( $new_submission_id ) ) {
		return $cmb->prop( 'submission_error', $new_submission_id );
	}



				// Get our shortcode attributes and set them as our initial post_data args
				if ( isset( $_POST['atts'] ) ) {
					foreach ( (array) $_POST['atts'] as $key => $value ) {
						$post_data[ $key ] = sanitize_text_field( $value );
					}
					unset( $_POST['atts'] );
				}

				// Check security nonce
				if ( ! isset( $_POST[ $cmb->nonce() ] ) || ! wp_verify_nonce( $_POST[ $cmb->nonce() ], $cmb->nonce() ) ) {
					return $cmb->prop( 'submission_error', new WP_Error( 'security_fail', __( 'Security check failed.' ) ) );
				}

				/**
				 * Fetch sanitized values
				 */
				$sanitized_values =  $cmb->get_sanitized_values( $_POST ) ;

	$cmb->save_fields( $new_submission_id, 'post', $sanitized_values );

			}






	/**
	 * Other than post_type and post_status, we want
	 * our uploaded attachment post to have the same post-data
	 */
/*	unset( $post_data['post_type'] );
	unset( $post_data['post_status'] );

	// Try to upload the featured image
	$img_id = yourprefix_frontend_form_photo_upload( $new_submission_id, $post_data );

	// If our photo upload was successful, set the featured image
	if ( $img_id && ! is_wp_error( $img_id ) ) {
		set_post_thumbnail( $new_submission_id, $img_id );
	}*/

	/*
	 * Redirect back to the form page with a query variable with the new post ID.
	 * This will help double-submissions with browser refreshes
	 */
	wp_redirect( esc_url_raw( add_query_arg( 'post_submitted', $new_submission_id ) ) );
	exit;
}


	protected function get_post_id_from_shortcode_atts( $post_type, $id_or_slug = 0 ) {

		$post_id = 0;

		// if story isn't set, attempt to get from global $post
		if ( $id_or_slug === 0 ) {
			global $post;
			if ( $post ) {
				$post_id = $post->ID;
			}
		} else {
			// if an integer is sent, use it as an id
			if ( is_numeric( $id_or_slug ) ) {
				$post_id = intval( $id_or_slug );
			} else {
				// otherwise try getting it by slug
				$cpt = get_page_by_path( $id_or_slug, OBJECT, $post_type );
				if ( $cpt ) {
					$post_id = $cpt->ID;
				}
			}
		}

		return $post_id;

	}

	protected function get_story_from_shortcode_atts( $story = 0 ) {
		$story_id = $this->get_post_id_from_shortcode_atts( 'mbdsc_story', $story );

		return new Mooberry_Story_Community_Story( $story_id );

	}

	protected function get_chapter_from_shortcode_atts( $chapter = 0 ) {
		$chapter_id = $this->get_post_id_from_shortcode_atts( 'mbdsc_chapter', $chapter );

		return new Mooberry_Story_Community_Chapter( $chapter_id );

	}

	protected function get_review_from_shortcode_atts( $review = 0 ) {
		$review_id = $this->get_post_id_from_shortcode_atts( 'mbdsc_review', $review );

		return new Mooberry_Story_Community_Review( $review_id );
	}

	protected function get_author_from_shortcode_atts( $author = 0 ) {

		$author_id = 0;
		if ( $author == 0 ) {
			$author_id = get_current_user_id();
		} else {
			if ( is_numeric( $author ) ) {
				$author_id = intval( $author );
			} else {
				$user = get_user_by( 'login', $author );
				if ( $user ) {
					$author_id = $user->ID;
				}

			}
		}

		return new Mooberry_Story_Community_Author( $author_id );
	}


	public function get_story_from_chapter( $story = 0 ) {
		// either a specific story has been passed in
		// or we need to figure out the story based on the chapter, which is the current $post

		if ( $story == 0 ) {
			$chapter_id = 0;
			global $post;
			if ( $post ) {
				$chapter_id = $post->ID;
			}
			$chapter = new Mooberry_Story_Community_Chapter( $chapter_id );
			$story   = intval( $chapter->story_id );
		}

		return $this->get_story_from_shortcode_atts( $story );

	}

	public function shortcode_title( $atts, $content ) {

		$atts  = shortcode_atts( array(
			'story' => null,
			'link'  => 'yes',
		), $atts );
		$story = $this->get_story_from_shortcode_atts( $atts['story'] );
		if ( $story ) {
			$content = $story->title;
			if ( $atts['link'] == 'yes' ) {
				$content = '<a href="' . $story->link . '">' . $content . '</a>';
			}


		}

		return apply_filters( 'mbdsc_title_shortcode', $content, $atts );
	}

	public function shortcode_author( $atts, $content ) {
		$atts  = shortcode_atts( array(
			'story'  => null,
			'byline' => 'no',
		), $atts );
		$story = $this->get_story_from_shortcode_atts( $atts['story'] );
		if ( $story ) {
			$content = '<div class="mbdsc_author">';
			if ( $atts['byline'] == 'yes' ) {
				$content .= '<span class="mbdsc_author_byline">By </span>';
			}
			$content .= '<a href="' . $story->author->link . '">' . $story->author->display_name . '</a></div>';

		}

		return apply_filters( 'mbdsc_author_shortcode', $content, $atts );
	}

	public function shortcode_cover( $atts, $content ) {
		$atts  = shortcode_atts( array(
			'story' => null,
			'link'  => 'no',
		), $atts );
		$story = $this->get_story_from_shortcode_atts( $atts['story'] );
		if ( $story ) {
			$content = '<img class="mbdsc_cover_image" src="' . esc_attr( $story->cover ) . '">';
			if ( $atts['link'] == 'yes' ) {
				$content = '<a href="' . $story->link . '">' . $content . '</a>';
			}

		}

		return apply_filters( 'mbdsc_cover_shortcode', $content, $atts );
	}

	public function shortcode_summary( $atts, $content ) {
		$atts  = shortcode_atts( array(
			'story' => null,

		), $atts );
		$story = $this->get_story_from_shortcode_atts( $atts['story'] );
		if ( $story ) {
			$content = '<div class="mbdsc_story_summary">';

			$content .= '<p>' . preg_replace( '/\\n/', '</p><p>', esc_html( $story->summary ) ) . '</p>';
			$content .= '</div>';
		}


		return apply_filters( 'mbdsc_summary_shortcode', $content, $atts );
	}

	public function shortcode_complete( $atts, $content ) {
		$atts  = shortcode_atts( array(
			'story' => null,
		), $atts );
		$story = $this->get_story_from_shortcode_atts( $atts['story'] );
		if ( $story ) {
			$complete = $story->is_complete ? 'Yes' : 'No';
			$content  = '<div class="mbdsc_story_complete">';
			$content  .= '<span class="mbdsc_story_complete_label">Story Completed:</span> <span class="mbdsc_story_complete_value">' . $complete . '</span>';
			$content  .= '</div>';
		}

		return apply_filters( 'mbdsc_complete_shortcode', $content, $atts );
	}

	public function shortcode_story_word_count( $atts, $content ) {
		$atts  = shortcode_atts( array(
			'story' => null,
		), $atts );
		$story = $this->get_story_from_shortcode_atts( $atts['story'] );
		if ( $story ) {
			$content = '<div class="mbdsc_story_word_count">';
			$content .= '<span class="mbdsc_story_word_count_label">Words:</span> <span class="mbdsc_story_word_count_value">' . $story->word_count . '</span>';
			$content .= '</div>';
		}

		return apply_filters( 'mbdsc_story_word_count_shortcode', $content, $atts );
	}

	protected function custom_field_output( $object, $atts ) {
		$field   = isset( $object->custom_fields[ $atts['field'] ] ) ? $object->custom_fields[ $atts['field'] ] : null;
		$content = '';
		if ( $field && $field->value != '' ) {
			$content = '<div class="mbdsc_story_custom_field mbdsc_story_custom_field_' . $field->unique_id . '">';
			$content .= '<span class="mbdsc_story_custom_field_label mbdsc_story_custom_field_' . $field->unique_id . '_label">' . $field->name . ':</span> <span class="mbdsc_story_custom_field_value mbdsc_story_custom_field_' . $field->unique_id . '_value">' . $field->value . '</span>';
			$content .= '</div>';

		}

		return apply_filters( 'mbdsc_custom_field_shortcode', $content );
	}

	public function shortcode_custom_field_story( $atts, $content ) {

		$atts = shortcode_atts( array(
			'story' => null,
			'field' => '',
		), $atts );

		$story = $this->get_story_from_shortcode_atts( $atts['story'] );
		if ( $story && $atts['field'] != '' ) {
			$content = $this->custom_field_output( $story, $atts );
		}

		return apply_filters( 'mbdsc_custom_field_story_shortcode', $content, $atts );
	}

	public function shortcode_custom_field_chapter( $atts, $content ) {

		$atts = shortcode_atts( array(
			'chapter' => null,
			'field'   => '',
		), $atts );

		$chapter = $this->get_chapter_from_shortcode_atts( $atts['chapter'] );
		if ( $chapter && $atts['field'] != '' ) {
			$content = $this->custom_field_output( $chapter, $atts );
		}

		return apply_filters( 'mbdsc_custom_field_chapter_shortcode', $content, $atts );
	}


	public function shortcode_taxonomy_field( $atts, $content ) {
		$atts  = shortcode_atts( array(
			'story' => null,
			'tax'   => '',
		), $atts );
		$story = $this->get_story_from_shortcode_atts( $atts['story'] );

		if ( $story && $atts['tax'] != '' ) {
			$taxonomy = isset( $story->taxonomies[ $atts['tax'] ] ) ? $story->taxonomies[ $atts['tax'] ] : null;
			$content  = '<div class="mbdsc_story_taxonomy mbdsc_story_taxonomy_' . $taxonomy->taxonomy . '">';
			$content  .= '<span class="mbdsc_story_taxonomy_label mbdsc_story_taxonomy_' . $taxonomy->taxonomy . '_label">' . $taxonomy->name . ':</span> <span class="mbdsc_story_taxonomy_value mbdsc_story_taxonomy_' . $taxonomy->taxonomy . '_value">' . get_the_term_list( $story->id, $taxonomy->taxonomy, '', ', ' ) . '</span>';
			$content  .= '</div>';
		}

		return apply_filters( 'mbdsc_taxonomy_field_shortcode', $content, $atts );
	}

	public function shortcode_toc( $atts, $content ) {

		$atts  = shortcode_atts( array(
			'story'        => null,
			'word_count'   => 'no',
			'review_count' => 'no',
		), $atts );
		$story = $this->get_story_from_shortcode_atts( $atts['story'] );
		if ( $story ) {
			$content = '<div class="mbdsc_toc"><h2 class="mbdsc_toc_title">' . __( 'Table of Contents', 'mooberry-story-community' ) . '</h2>';


			$content  .= '</div><ol class="mbdsc_toc_list">';
			$chapters = $story->chapters;
			foreach ( $chapters as $chapter ) {

				$content .= '<li><a class="mbdsc_toc_chapter_link" href="' . esc_attr( $chapter->link ) . '">';

				$content .= '<span class="mbdsc_toc_chapter_title">' . esc_html( $chapter->title ) . '</span></a>';

				if ( $atts['word_count'] === 'yes' ) {
					$content .= ' <span class="mbdsc_toc_chapter_word_count">( ' . $chapter->word_count . ' ' . _n( 'word', 'words', $chapter->word_count, 'mooberry-story-community' ) . ')</span>';
				}
				if ( $atts['review_count'] === 'yes' ) {
					$content .= ' <span class="mbdsc_toc_chapter_review_count">( ' . $chapter->review_count . ' ' . _n( 'review', 'reviews', $chapter->review_count, 'mooberry-story-community' ) . ')</span>';

				}

				$content .= '</li>';
			}
			$content .= '</ol>';
			//	$content .= '</div>';

		}

		return apply_filters( 'mbdsc_toc_shortcode', $content, $atts );
	}

	public function shortcode_toc_link( $atts, $content ) {
		$atts  = shortcode_atts( array(
			'story' => null,
		), $atts );
		$story = $this->get_story_from_chapter( $atts['story'] );
		if ( $story ) {
			$content = '<a class="mbdsc_toc_link" href="' . esc_attr( $story->link ) . '">' . __( 'Table of Contents', 'mooberry-story-community' ) . '</a>';
		}

		return apply_filters( 'mbdsc_toc_link_shortcode', $content, $atts );
	}

	protected function next_prev_chapter_link( $atts, $content, $next ) {
		$atts  = shortcode_atts( array(
			'story' => null,
		), $atts );
		$story = $this->get_story_from_chapter( $atts['story'] );
		if ( $story ) {
			global $post;
			if ( $post ) {
				$chapter_id = $post->ID;
				if ( $next ) {
					$chapter = $story->get_next_chapter( $chapter_id );
					$text    = 'Next';
				} else {
					$chapter = $story->get_prev_chapter( $chapter_id );
					$text    = 'Previous';
				}

				if ( $chapter ) {

					$content = '<div class="mbdsc_' . strtolower( $text ) . '">' . $text . ' Chapter: <a href="' . $chapter->link . '">';
					/*			if ( isset( $mbds_story['_mbds_include_posts_name'] ) ) {
									$content .= '<span class="mbs_' . $nextprev . '_posts_name">' . mbds_display_posts_name( $mbds_story, $posts[ $found ]['ID'] ) . ': </span>';
								}*/
					$content .= $chapter->title . '</a>';
					$content .= '</div>';
				}
			}
		}

		return apply_filters( 'mbdsc_' . strtolower( $text ) . '_shortcode', $content, $atts );

	}

	public function shortcode_next( $atts, $content ) {
		return $this->next_prev_chapter_link( $atts, $content, true );
	}

	public function shortcode_prev( $atts, $content ) {
		return $this->next_prev_chapter_link( $atts, $content, false );
	}

	private function display_hierarchial_list( $terms, $expandable = true ) {
		$content = '';
		foreach ( $terms as $term ) {
			// The $term is an object, so we don't need to specify the $taxonomy.
			$link = get_term_link( $term );

			// If there was an error, continue to the next term.
			if ( is_wp_error( $link ) ) {
				continue;
			}

			$has_children = isset( $term->children ) && count( $term->children ) > 0;

			// get icon
			$icon = '';
			if ( $expandable ) {
				$icon = $has_children ? 'moobdir_region_menu_toggle moobdir_region_menu_closed dashicons dashicons-arrow-right-alt2' : 'moobdir_region_menu_leaf dashicons dashicons-minus';
			}


			$content .= '<li class="moobdir_region_menu_item cat-item cat-item-' . $term->term_id . '"><span class="' . $icon . '"></span><a href="' . esc_url( $link ) . '">' . $term->name . '</a>';
			if ( $has_children ) {
				$content .= '<ul class="children">';
				$content .= $this->display_hierarchial_list( $term->children, $expandable );
				$content .= '</ul>';
			}
			$content .= '</li>';
		}

		return $content;
	}

	public function shortcode_author_pic( $atts, $content ) {
		$atts   = shortcode_atts( array(
			'author' => null,
			'height' => '',
			'width'  => '100px',
			'class'  => '',
		), $atts );
		$author = $this->get_author_from_shortcode_atts( $atts['author'] );
		$class  = $atts['class'];
		$width  = $atts['width'];
		$height = $atts['height'];
		$width  = $width != '' ? " width: $width; " : '';
		$height = $height != '' ? " height: $height; " : '';
		$class  = $class != '' ? " class='$class' " : '';

		$style = 'style="';
		if ( $class == '' ) {
			if ( $width != '' ) {
				$style .= $width;
			}
			if ( $height != '' ) {
				$style .= $height;
			}
		}
		$style .= '"';

		$content = '<span class="mbdsc_author_profile_pic"><img src="' . esc_attr( $author->avatar ) . '" ' . $class . ' ' . $style . ' /></span>';

		return apply_filters( 'mbdsc_author_profile_pic_shortcode', $content, $atts );

	}

	public function shortcode_author_bio( $atts, $content ) {
		$atts   = shortcode_atts( array(
			'author' => null,
		), $atts );
		$author = $this->get_author_from_shortcode_atts( $atts['author'] );

		$content = '<div class="mbdsc_author_bio">' . $author->bio . '</div>';

		return apply_filters( 'mbdsc_author_bio_shortcode', $content, $atts );

	}

	public function shortcode_author_stories( $atts, $content ) {
		$atts   = shortcode_atts( array(
			'author' => null,

		), $atts );
		$author = $this->get_author_from_shortcode_atts( $atts['author'] );

		$stories = Mooberry_Story_Community_Story_Collection::get_stories_by_user( $author->user_id );
		foreach ( $stories as $story ) {
			$content .= '<div class="mbdsc_author_page_story"><h2 class="entry-title">[mbdsc_title link-"yes" story="' . $story->slug . '"]</h2><div class="entry-content">';
			$content .= '[mbdsc_cover link="yes" story="' . $story->slug . '"]';

			$content .= '[mbdsc_summary story="' . $story->slug . '"]';

			$content .= '</div></div>';


		}

		return do_shortcode( apply_filters( 'mbdsc_author_stories_shortcode', $content, $atts ) );

	}

	public function shortcode_review_form( $atts, $content ) {
		$atts = shortcode_atts( array(
			'chapter' => 0,
		), $atts );

		$chapter    = $this->get_chapter_from_shortcode_atts( $atts['chapter'] );
		$show_email = Mooberry_Story_Community_Main_Settings::get_review_show_email();

		ob_start();
		include MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'public/partials/review-form.php';
		$content = ob_get_clean();

		return do_shortcode( apply_filters( 'mbdsc_review_form_shortcode', $content, $atts ) );
	}

	public function shortcode_chapter_reviews( $atts, $content ) {
		$atts = shortcode_atts( array(
			'chapter' => 0,
		), $atts );

		$content = '<div class="mbdsc_chapter_reviews">';
		$chapter = $this->get_chapter_from_shortcode_atts( $atts['chapter'] );
		if ( count( $chapter->reviews ) == 0 ) {
			$content .= '<div id="mbdsc_chapter_reviews_none">No Reviews Found</div>';
		}
		foreach ( $chapter->reviews as $review ) {
			$content .= '[mbdsc_review review="' . $review->id . '"]<hr>';
		}
		$content .= '</div>';

		return do_shortcode( apply_filters( 'mbdsc_chapter_reviews_shortcode', $content, $atts ) );
	}

	public function shortcode_review( $atts, $content ) {
		$atts = shortcode_atts( array(
			'review' => null,
		), $atts );

		$review         = $this->get_review_from_shortcode_atts( $atts['review'] );
		$show_email     = Mooberry_Story_Community_Main_Settings::get_review_show_email();
		$review_content = str_replace( array( "\r", "\n", "\r\n" ), '<br>', $review->review_content );

		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );

		$timestamp = date( $date_format . ' ' . $time_format, strtotime( $review->timestamp ) );
		ob_start();
		include MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'public/partials/review-single.php';
		$content = ob_get_clean();

		return $content;

	}

	public function shortcode_chapter_review_count( $atts, $content ) {
		$atts = shortcode_atts( array(
			'chapter' => 0,
		), $atts );


		$chapter = $this->get_chapter_from_shortcode_atts( $atts['chapter'] );
		$content = '<span class="mbdsc_chapter_review_count">' . $chapter->review_count . '</span>';

		return apply_filters( 'mbdbsc_chapter_review_count_shortcode', $content, $atts );
	}

	public function shortcode_account_page( $atts, $content ) {


		$account_tabs = apply_filters( 'mbdsc_account_tabs', array(
			'stories'       => __( 'Stories', 'mooberry-story-community' ),
			'notifications' => __( 'Notifications', 'mooberry-story-community' ),
			'profile'       => __( 'Profile', 'mooberry-story-community' ),
		) );

		$content = '<div id="mbdsc_account_page_tabs"><ul>';
		$tabs    = '';
		$pages   = '';
		foreach ( $account_tabs as $tab => $title ) {
			$tabs  .= "<li><a href='#$tab'>$title</a></li>";
			$pages .= "<div id='$tab'>";
			ob_start();
			include MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . "/public/partials/$tab.php";
			$pages .= ob_get_clean();
			$pages .= "</div>";
		}

		$content .= $tabs . '</ul>' . $pages;

		return apply_filters( 'mbdsc_account_page_shortcode', $content, $atts );
	}

	public function shortcode_edit_story_page( $atts, $content ) {
		$story_id = absint( $_GET['story_id'] );
		if ( $story_id > 0 ) {

			$content .= '<h2>Chapters</h2>';
			ob_start();
			include MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'public/partials/chapters.php';
			$content .= ob_get_clean();

			$edit_chapter_page = Mooberry_Story_Community_Main_Settings::get_edit_chapter_page();

			$content .= '<a href="' . esc_attr( get_permalink( $edit_chapter_page ) . '?story_id=' . $story_id . '&chapter_id=0' ) . '" ><button>Add Chapter</button></a>';

			$content .= '<hr>';

		} else {
			$story_id = 'new_story';
		}
			$content .= '<div style="margin-top:2em;">';

			$content .= '<h2>Story Options</h2>';
			$content .= '<form class="cmb-form" method="post" id="mbdsc_story_meta_box" enctype="multipart/form-data" encoding="multipart/form-data">
    <input type="hidden" name="object_id" value="' . $story_id . '">';

			$args      = array( 'form_format' => '%3$s', 'echo' => false );
			$metaboxes = array( 'mbdsc_story_meta_box', 'mbdsc_cover_image' );

			$custom_taxonomies = Mooberry_Story_Community_Custom_Taxonomies_Settings::get_taxonomies();
			foreach ( $custom_taxonomies as $taxonomy ) {



					$metaboxes[] = $taxonomy->taxonomy . '_taxonomy_public_metabox';

			}


			foreach ( $metaboxes as $metabox ) { // loop over config array
				$content .= cmb2_metabox_form( $metabox, $story_id, $args );
			}

			$content .= '<input type="submit" name="submit-cmb" value="Save" class="button-primary"></form>';

			$content .= '</div>';


			/*$content = cmb2_get_metabox_form( 'mbdsc_story_meta_box', $story_id );
			$content .= cmb2_get_metabox_form( 'mbdsc_cover_image', $story_id );*/


		return apply_filters( 'mbdsc_edit_story_page_shortcode', $content, $atts );
	}
}
/*

function custom_post_author_archive($query) {
    if ($query->is_author)
        $query->set( 'post_type', array('mbdsc_story') );
    remove_action( 'pre_get_posts', 'custom_post_author_archive' );
}
add_action('pre_get_posts', 'custom_post_author_archive');*/
