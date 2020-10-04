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

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mooberry-story-community-public.js', array( 'jquery' ), $this->version, false );

	}

	protected function get_post_id_from_shortcode_atts(  $post_type, $id_or_slug = 0 ) {

		$post_id = 0;

		// if story isn't set, attempt to get from global $post
		if ( $id_or_slug == 0 ) {
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
		$chapter_id = $this->get_post_id_from_shortcode_atts( 'mbdsc_chapter', $chapter);
		return new Mooberry_Story_Community_Chapter( $chapter_id );

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

	public function shortcode_cover( $atts, $content ) {
		$atts  = shortcode_atts( array(
			'story' => null,
			'link'  => 'no',
		), $atts );
		$story = $this->get_story_from_shortcode_atts( $atts['story'] );
		if ( $story ) {
			$content = '<img class="mbdsc_cover_image" src="' . esc_attr( $story->cover ) . '">';
			if ( $atts['link'] == 'yes') {
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

	protected function custom_field_output ( $object, $atts ) {
			$field   = isset( $object->custom_fields[ $atts['field'] ] ) ? $object->custom_fields[ $atts['field'] ] : null;
			$content = '';
			if ( $field && $field->value != '' ) {
				$content = '<div class="mbdsc_story_custom_field mbdsc_story_custom_field_' . $field->unique_id . '">';
				$content .= '<span class="mbdsc_story_custom_field_label mbdsc_story_custom_field_' . $field->unique_id . '_label">' . $field->name . ':</span> <span class="mbdsc_story_custom_field_value mbdsc_story_custom_field_' . $field->unique_id . '_value">' . $field->value . '</span>';
				$content .= '</div>';

			}
			return apply_filters('mbdsc_custom_field_shortcode', $content);
	}

	public function shortcode_custom_field_story( $atts, $content ) {

		$atts = shortcode_atts( array(
			'story' => null,
			'field' => ''
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
			'field' => ''
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
			$content  .= '<span class="mbdsc_story_taxonomy_label mbdsc_story_taxonomy_' . $taxonomy->taxonomy . '_label">' . $taxonomy->name . ':</span> <span class="mbdsc_story_taxonomy_value mbdsc_story_taxonomy_' . $taxonomy->taxonomy . '_value">' . get_the_term_list( $story->id, $taxonomy->taxonomy ) . '</span>';
			$content  .= '</div>';
		}

		return apply_filters( 'mbdsc_taxonomy_field_shortcode', $content, $atts );
	}

	public function shortcode_toc( $atts, $content ) {

		$atts  = shortcode_atts( array(
			'story' => null,
		), $atts );
		$story = $this->get_story_from_shortcode_atts( $atts['story'] );
		if ( $story ) {
			$content = '<div class="mbdsc_toc"><h2 class="mbdsc_toc_title">' . __( 'Table of Contents', 'mooberry-story-community' ) . '</h2>';


			$content  .= '</div><ol class="mbdsc_toc_list">';
			$chapters = $story->chapters;
			foreach ( $chapters as $chapter ) {

				$content .= '<li><a class="mbdsc_toc_chapter_link" href="' . esc_attr( $chapter->link ) . '">';

				$content .= '<span class="mbdsc_toc_chapter_title">' . esc_html( $chapter->title ) . '</span></a></li>';

				//$content .= ' <span class="mbsc_toc_item_word_count">(' . mbds_get_word_count( get_post_field( 'post_content', $each_post['ID'] ) ) . ' words)</span></li>';
			}
			$content .= '</ol>';
			$content .= '</div>';

		}

		return apply_filters( 'mbdsc_toc_shortcode', $content, $atts );
	}

	public function shortcode_toc_link( $atts, $content ) {
		$atts  = shortcode_atts( array(
			'story' => null,
		), $atts );
		$story = $this->get_story_from_chapter( $atts['story'] );
		if ( $story ) {
			$content = '<a class="mbdsc_toc_link" href="' . get_permalink( $story->id ) . '">' . __( 'Table of Contents', 'mooberry-story-community' ) . '</a>';
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
				$chapter_id   = $post->ID;
				if ( $next ) {
					$chapter = $story->get_next_chapter( $chapter_id );
					$text = 'Next';
				} else {
					$chapter = $story->get_prev_chapter( $chapter_id );
					$text = 'Previous';
				}

				if ( $chapter ) {

					$content = '<div class="mbdsc_' . strtolower($text) . '">' . $text . ' Chapter: <a href="' . $chapter->link . '">';
		/*			if ( isset( $mbds_story['_mbds_include_posts_name'] ) ) {
						$content .= '<span class="mbs_' . $nextprev . '_posts_name">' . mbds_display_posts_name( $mbds_story, $posts[ $found ]['ID'] ) . ': </span>';
					}*/
					$content .= $chapter->title . '</a>';
					$content .= '</div>';
				}
			}
		}
		return apply_filters( 'mbdsc_' . strtolower($text)  . '_shortcode', $content, $atts );

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


}
