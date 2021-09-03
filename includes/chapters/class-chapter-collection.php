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

	public static function get_all_published_chapters( ) {
		$chapters = self::get(array('post_status'=>'publish' ));
		return self::get_chapter_objects($chapters);
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
		global $mbdsc_chapter_factory;
		foreach ( $chapters as $chapter ) {

			$chapter_objects[] = $mbdsc_chapter_factory->create_chapter( $chapter->ID );
		}

		return $chapter_objects;
	}

	public static function get_chapter_count_for_story( $story_id ) {
		return count( self::get_chapters_by_story( $story_id ) );
	}

	public static function get_chapters_updated_since( $timestamp ) {
		$chapters = self::get(array(
			'post_status'=>'publish',
			 'date_query' => array(
			 	'relation' => 'OR',
        array(
            'column' => 'post_date_gmt',
            'after' => $timestamp,
        ),
        array(
            'column' => 'post_modified_gmt',
            'after'  => $timestamp,
        ),
    ),
			));
		return self::get_chapter_objects($chapters);
	}


	protected static function get_chapter_objects( $chapters ){
		$chapter_objects = array();
		global $mbdsc_chapter_factory;
		foreach ( $chapters as $chapter ) {
			$chapter_objects[] = $mbdsc_chapter_factory->create_chapter( $chapter->ID );
		}

		return $chapter_objects;
	}
}
