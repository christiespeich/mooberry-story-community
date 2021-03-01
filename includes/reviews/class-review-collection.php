<?php


class Mooberry_Story_Community_Review_Collection {

	public static function get( $args = array() ) {

		$defaults = array(
			'post_type'      => 'mbdsc_review',
			'posts_per_page' => - 1,
		);

		$args = array_merge( $args, $defaults );

		return get_posts( $args );
	}


	public static function get_reviews_by_user( $email ) {
		return self::get( array( 'meta_value' => $email, 'meta_key'=>'mbdsc_reviewer_email' ) );
	}


	public static function get_reviews_by_story( $story_id ) {
		$reviews = self::get( array(
			'meta_key'   => 'mbdsc_review_story',
			'meta_value' => $story_id
		) );
		return self::get_review_objects($reviews);
	}

		public static function get_reviews_by_chapter( $chapter_id ) {
		$reviews = self::get( array(
			'meta_key'   => 'mbdsc_review_chapter',
			'meta_value'    =>  $chapter_id
		) );


		return self::get_review_objects($reviews);
	}

	public static function get_review_count_for_story( $story_id ) {
		return count( self::get_reviews_by_story( $story_id ) );
	}

	public static function get_review_count_for_chapter( $chapter_id ) {
		return count( self::get_reviews_by_chapter( $chapter_id ) );
	}

	protected static function get_review_objects( $reviews ){
		$review_objects = array();
		global $mbdsc_review_factory;
		foreach ( $reviews as $review ) {
			$review_objects[] = $mbdsc_review_factory->create_review(intval($review->ID));
		}
		return $review_objects;
	}
}
