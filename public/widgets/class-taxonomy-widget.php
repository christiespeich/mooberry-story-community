<?php


class Moobery_Story_Community_Taxonomy_Widget extends WP_Widget {

	function __construct() {
		 parent::__construct(

			// base ID of the widget
			'mbdsc_taxonomy_widget',

			// name of the widget
			__('Story Community - Taxonomy', 'mooberry-story-community' ),

			// widget options
			array (
				'description' => __( 'Browse a list of taxonomy terms of stories.', 'mooberry-story-community' ),
				'classname' => 'Moobery_Story_Community_Taxonomy_Widget',
			)

		);
	}

	function form( $instance ) {

	 	if ($instance) {

			$mbds_sw_title = $instance['mbds_sw_title'];
			$mbdsc_taxonomy = $instance['mbdsc_taxonomy'];

		} else {
			$mbdsc_taxonomy = '';
			$mbds_sw_title = '';

		}

	 	$taxonomies = get_object_taxonomies('mbdsc_story', 'objects');
		include MOOBERRY_STORY_COMMUNITY_PLUGIN_DIR . 'public/partials/widget-taxonomy-menu.php';
	}

		function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['mbdsc_taxonomy'] = strip_tags( $new_instance['mbdsc_taxonomy']);
		$instance['mbds_sw_title'] = strip_tags($new_instance['mbds_sw_title']);
		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args );
		echo $before_widget;
		echo $before_title . esc_html($instance['mbds_sw_title']) . $after_title;


		//$regions         = $this->get_terms_with_stories();
		$terms = get_terms( array( 'taxonomy'=> $instance['mbdsc_taxonomy']));
		$termHierarchy = array();
		$this->sort_terms_hierarchicaly( $terms, $termHierarchy );
		echo  '<ul class="mbdsc_taxonomy_menu">';
		echo $this->display_hierarchial_list( $termHierarchy, true );
		echo '</ul>';



		echo $after_widget;

	}

	/**
	 * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
	 * placed under a 'children' member of their parent term.
	 *
	 * @param Array   $cats     taxonomy term objects to sort
	 * @param Array   $into     result array to put them in
	 * @param integer $parentId the current parent ID to put them in
	 */
	private function sort_terms_hierarchicaly( Array &$cats, Array &$into, $parentId = 0 ) {
		foreach ( $cats as $i => $cat ) {
			if ( $cat->parent == $parentId ) {
				$into[ $cat->term_id ] = $cat;
				unset( $cats[ $i ] );
			}
		}

		foreach ( $into as $topCat ) {
			$topCat->children = array();
			$this->sort_terms_hierarchicaly( $cats, $topCat->children, $topCat->term_id );
		}
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
				$icon = $has_children ? 'mbdsc_taxonomy_menu_toggle mbdsc_taxonomy_menu_closed dashicons dashicons-arrow-right-alt2' : 'mbdsc_taxonomy_menu_leaf dashicons dashicons-minus';
			}


			$content .= '<li class="mbdsc_taxonomy_menu_item cat-item cat-item-' . $term->term_id . '"><span class="' . $icon . '"></span><a href="' . esc_url( $link ) . '">' . $term->name . '</a>';
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
