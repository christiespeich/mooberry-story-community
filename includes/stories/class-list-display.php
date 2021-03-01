<?php


class Mooberry_Story_Community_Story_List_Display {

	protected $stories;

	public function __construct( $stories ) {
		$this->stories = $stories;
	}

	public function display() {
		$content = '';
			foreach ( $this->stories as $story ) {
			$content .= '<div class="mbdsc_story_list"><h2 class="entry-title">[mbdsc_title link-"yes" story="' . $story->slug . '"]</h2><div class="entry-content">';
			$content .= '[mbdsc_cover link="yes" story="' . $story->slug . '"]';

			$content .= '[mbdsc_summary story="' . $story->slug . '"]';
			$content .= '<p><a href="' . $story->get_chapter_link(1) . '">' . __('Read now...', 'mooberry-story-community') . '</a></p>';
			$content .= '<hr>';
			$content .= '[mbdsc_chapter_count story="' . $story->slug . '"] | [mbdsc_story_review_count story="' . $story->slug . '"] | [mbdsc_complete story="' . $story->slug . '"]';
			$content .= '</div></div>';



		}
			return do_shortcode($content);
	}
}
