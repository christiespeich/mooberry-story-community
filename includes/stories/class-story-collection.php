<?php


class Mooberry_Story_Community_Story_Collection {

	public static function get( $args = array() ) {

		$defaults = array(
			'post_type' =>  'mbdsc_story',
			'posts_per_page'    =>  -1,
		);

		$args = array_merge( $args, $defaults );

		 return get_posts($args);


	}

	public static function get_all_published_stories( ) {
		$stories = self::get(array('post_status'=>'publish' ));
		return self::get_story_objects($stories);
	}

	public static function get_stories_by_user( $user_id ) {
		$stories = self::get( array('author' => $user_id));

			$story_objects = array();
		foreach ( $stories as $story ) {
			$story_objects[] = new Mooberry_Story_Community_Story(intval($story->ID));
		}
		return $story_objects;
	}

	public static function get_recently_updated_stories( $limit = 0 ) {
		$updated_posts = get_posts( array(
			'orderby' => 'post_modified',
			'order' =>  'DESC',
			'post_type' =>  array('mbdsc_chapter', 'mbdsc_story'),
			'post_status' => 'publish',
			'posts_per_page' => -1,
		) );

		$story_ids = array();
		$count = 0;
		foreach ( $updated_posts as $post ) {
			if ( $post->post_type == 'mbdsc_chapter') {
				$story_id = get_post_meta( $post->ID, 'mbdsc_chapter_story', true );
				if ( get_post_status( $story_id) != 'publish' ) {
					continue;
				}
			} else {
				$story_id = $post->ID;
			}
			if ( $story_id == '' ) {
				continue;
			}
			if  (!in_array($story_id, $story_ids)) {
				$story_ids[] = $story_id;
				$count++;
			}
			if ( $count == $limit ) {
				break;
			}
		}


		$story_objects = array();
		foreach ( $story_ids as $story ) {
			$story_objects[] = new Mooberry_Story_Community_Story(intval($story));
		}
		return $story_objects;
	}

	protected static function get_story_objects( $stories ){
		$story_objects = array();
		foreach ( $stories as $story ) {
			$story_objects[] = new Mooberry_Story_Community_Story(intval($story->ID));
		}
		return $story_objects;
	}
}
