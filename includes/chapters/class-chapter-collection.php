<?php


class Mooberry_Story_Community_Chapter_Collection {

	public static function get( $args = array() ) {

		$defaults = array(
			'post_type'      => 'mbdsc_chapter',
			'posts_per_page' => - 1,
		);

		$args = array_merge( $args, $defaults );

		return get_posts( $args );
	}

	public static function get_chapters_by_user( $user_id ) {
		return self::get( array( 'author' => $user_id ) );
	}


	public static function get_chapters_by_story( $story_id ) {
		$chapters = self::get( array(
			'orderby'    => 'meta_value_num',
			'meta_type'  => 'numeric',
			'meta_key'   => '_mbdsc_chapter_order',
			'order'      => 'ASC',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'   => 'mbdsc_chapter_story',
					'value' => $story_id,
				),

				array(
					'relation' => 'OR',
					array(
						'key'     => '_mbdsc_chapter_order',
						'compare' => 'NOT EXISTS',
						'value'   => 'bug #23268',
					),
					array(
						'key'     => '_mbdsc_chapter_order',
						'compare' => 'EXISTS',
					),

				),
			),
		) );


		$chapter_objects = array();
		foreach ( $chapters as $chapter ) {
			$chapter_objects[] = new Mooberry_Story_Community_Chapter( $chapter->ID );
		}

		return $chapter_objects;
	}

	public static function get_chapter_count_for_story( $story_id ) {
		return count( self::get_chapters_by_story( $story_id ) );
	}
}
