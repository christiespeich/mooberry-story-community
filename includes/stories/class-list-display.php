<?php


class Mooberry_Story_Community_Story_List_Display {

	protected $stories;

	public function __construct( $stories ) {
		$this->stories = $stories;
	}

	public function display( $args = array() ) {
		$cover_height = $args['cover_height'] ?? '';
		$content      = apply_filters( 'mbdsc_list_display_before_list', '<div class="mbdsc_story_list">', $args, $this );
		foreach ( $this->stories as $story ) {
			$content = apply_filters( 'mbdsc_list_display_before_item', $content, $story, $args, $this );
			$content .= '<div class="mbdsc_story_list_item">';
			$content = apply_filters( 'mbdsc_list_display_before_title', $content, $story, $args, $this );
			$content .= '<h2 class="entry-title">[mbdsc_title link-"yes" story="' . $story->slug . '"]</h2>';
			$content = apply_filters( 'mbdsc_list_display_before_author', $content, $story, $args, $this );
			$content .= '[mbdsc_author byline="yes" story="' . $story->slug . '"]';
			$content .= '<div class="entry-content" >';
			$content = apply_filters( 'mbdsc_list_display_before_cover', $content, $story, $args, $this );
			$content .= '<div class="mbdsc_story_list_item_cover">';
			$content .= '[mbdsc_cover link="yes" story="' . $story->slug . '" height="' . $cover_height . '"]';
			$content .= '</div>';
			$content = apply_filters( 'mbdsc_list_display_before_summary', $content, $story, $args, $this );
			$content .= '<div class="mbdsc_story_list_item_info">';
			$content .= '[mbdsc_summary story="' . $story->slug . '"]';
			$content .= '<p><a href="' . $story->get_chapter_link( 1 ) . '">' . __( 'Read now...', 'mooberry-story-community' ) . '</a></p>';
			$content = apply_filters( 'mbdsc_list_display_after_summary',$content,  $story, $args, $this );
			$content .= '<hr>';
			$content = apply_filters( 'mbdsc_list_display_before_counts',$content,  $story, $args, $this );
			$content .= '[mbdsc_chapter_count story="' . $story->slug . '"] | [mbdsc_story_review_count story="' . $story->slug . '"] | [mbdsc_complete story="' . $story->slug . '"]';
			$content = apply_filters( 'mbdsc_list_display_after_counts', $content, $story, $args, $this );
			$content .= '</div> <!-- mbsc_story_list_item_info -->';
			$content .= '</div> <!-- entry-content -->';
			$content .= '</div> <!-- mbdsc_story_list_item -->';
			$content = apply_filters( 'mbdsc_list_display_after_item', $content, $story, $args, $this );


		}
		$content .= '</div> <!-- mbdsc_story_list -->';
		$content = apply_filters( 'mbdsc_list_display_after_list', $content, $args, $this );

		return apply_filters('mbdsc_list_display_content_post_shortcode', do_shortcode( $content ), $args, $this );
	}
}
