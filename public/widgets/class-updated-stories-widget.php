<?php


class Mooberry_Story_Community_Updated_Stories_Widget extends WP_Widget {
	function __construct() {
		 parent::__construct(

			// base ID of the widget
			'mbdsc_updated_stories_widget',

			// name of the widget
			__('Story Community - Updated Stories', 'mooberry-story-community' ),

			// widget options
			array (
				'description' => __( 'Displays a list of stories that have updated recently.', 'mooberry-story-community' ),
				'classname' => 'Mooberry_Story_Community_Updated_Stories_Widget',
			)

		);
	}

	function form( $instance ) {

	 	if ($instance) {

			$mbds_sw_title = $instance['mbds_sw_title'];
			$mbds_story_limit = $instance['mbds_story_limit'];

		} else {
			$mbds_sw_story_limit = '10';
			$mbds_sw_title = '';

		}

		include MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'public/partials/widget-updated-stories.php';
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['mbds_story_limit'] = strip_tags( $new_instance['mbds_story_limit']);
		$instance['mbds_sw_title'] = strip_tags($new_instance['mbds_sw_title']);
		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args );
		echo $before_widget;
		echo $before_title . esc_html($instance['mbds_sw_title']) . $after_title;

		$stories = Mooberry_Story_Community_Story_Collection::get_recently_updated_stories(10);

		if ($stories != null) {
			echo '<ul class="mbs_story_widget_list">';
			foreach ($stories as $story) {
				echo '<li><a href="' . $story->link . '">' . $story->title . '</a></li>';
			}
			echo '</ul>';
		} else {
			echo '<span class="mbs_story_widget_none">';
			echo __('No stories found', 'mooberry-story-community');
			echo '</span>';
		}
		echo $after_widget;

	}

}
