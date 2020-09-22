<?php


class Mooberry_Story_Community_Story_CPT extends Mooberry_Story_Community_CPT {

	public function __construct() {
		parent::__construct();

		$this->plural_name = 'Stories';
		$this->singular_name = 'Story';
		$this->post_type = 'mbdsc_story';
		$this->single = 'mbdsc_story';
		$this->plural = 'mbdsc_stories';

		// get taxonomies
	$custom_taxonomies = Mooberry_Story_Community_Custom_Taxonomies_Settings::get_taxonomies();
		foreach ( $custom_taxonomies as $custom_taxonomy ) {
			$single = isset( $custom_taxonomy['singular_name'] ) ? sanitize_text_field( $custom_taxonomy['singular_name'] ) : '';
			$plural = isset( $custom_taxonomy['plural_name'] ) ? sanitize_text_field( $custom_taxonomy['plural_name'] ) : '';
			$hierarchical = isset( $custom_taxonomy['hierarchical']) && $custom_taxonomy['hierarchical'] === 'yes';




			$tax    = 'mbdsc_' . sanitize_title( $single );

			$new_taxonomy = new Mooberry_Story_Community_Taxonomy( $tax, $this->post_type,  $single, $plural, array(
			//	'meta_box_cb'  => 'post_categories_meta_box',
				'capabilities' => array(
					'manage_terms' => 'add_' . $tax . '_terms', //'manage_categories',
					'edit_terms'   => 'edit_' . $tax . '_terms', //'manage_categories',
					'delete_terms' => 'delete_' . $tax . '_terms',
					'assign_terms' => 'assign_' . $tax . '_terms',
				),
				'hierarchical' => $hierarchical,
			) ) ;
			$this->add_taxonomy( $new_taxonomy );
		}

		$this->args = array(
			'public'          => true,
			'rewrite'         => array( 'slug' => 'story' ),
			'menu_icon'       => 'data:image/svg+xml;base64,PHN2ZyBpZD0iQ2FwYV8xIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA1MTIgNTEyIiBoZWlnaHQ9IjUxMiIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHdpZHRoPSI1MTIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGc+PGc+PHBhdGggZD0ibTI5NiA0MjJoLTEyMGMwLTguMzk1IDAtMzk4LjM1NiAwLTQxMmgxMjB6IiBmaWxsPSIjZDhlYzg0Ii8+PHBhdGggZD0ibTUwMiA0MjJ2NDBjLTc1LjEwNyAwLTQ3Ni4xNDUgMC00OTIgMHYtNDB6IiBmaWxsPSIjODY5MGE2Ii8+PHBhdGggZD0ibTM1NiAzMTBoMTIwdjExMmgtMTIweiIgZmlsbD0iI2ZmOTI1NiIvPjxwYXRoIGQ9Im00NzYgMTcwdjE0MGgtMTIwdi0xNDB6IiBmaWxsPSIjZWVmYWZmIi8+PHBhdGggZD0ibTM1NiAxMzBoMTIwdjQwaC0xMjB6IiBmaWxsPSIjZmY5MjU2Ii8+PGVsbGlwc2UgY3g9IjQxNiIgY3k9IjI0MCIgZmlsbD0iI2ZmOTI1NiIgcng9IjIwIiByeT0iMzAiLz48cGF0aCBkPSJtMzU2IDQyMmgtNjB2LTMzMmg2MHoiIGZpbGw9IiNmZmVlODAiLz48Y2lyY2xlIGN4PSIyMzYiIGN5PSI3MCIgZmlsbD0iI2VlZmFmZiIgcj0iMjAiLz48Y2lyY2xlIGN4PSIyMzYiIGN5PSIzNjIiIGZpbGw9IiNlZWZhZmYiIHI9IjIwIi8+PHBhdGggZD0ibTE3NS4zNiAxMTcuMzktMTUuNTMgNTcuOTYtNTcuOTUtMTUuNTMgMTUuNTMtNTcuOTZjMi44NS0xMC42NyAxMy44Mi0xNyAyNC40OS0xNC4xNGwxOS4zMiA1LjE4YzEwLjc3OCAyLjg5NSAxNi45ODIgMTMuOTU3IDE0LjE0IDI0LjQ5eiIgZmlsbD0iI2E0Y2FmZiIvPjxwYXRoIGQ9Im05My4wOTMgMTY2LjU2M2g1OS45OTV2NTkuOTk1aC01OS45OTV6IiBmaWxsPSIjZWVmYWZmIiB0cmFuc2Zvcm09Im1hdHJpeCguMjU5IC0uOTY2IC45NjYgLjI1OSAtOTguNjMzIDI2NC41NzQpIi8+PHBhdGggZD0ibTE0NC4zIDIzMy4zLTQ2LjU4IDE3My44N2MtMi44MzQgMTAuNTU2LTEzLjY3NyAxNy4wMTgtMjQuNSAxNC4xNGwtMTkuMzItNS4xN2MtMTAuNjctMi44Ni0xNy0xMy44My0xNC4xNC0yNC41bDQ2LjU5LTE3My44N3oiIGZpbGw9IiNhNGNhZmYiLz48L2c+PGc+PHBhdGggZD0ibTIzNiAxMDBjMTYuNTQyIDAgMzAtMTMuNDU4IDMwLTMwcy0xMy40NTgtMzAtMzAtMzAtMzAgMTMuNDU4LTMwIDMwIDEzLjQ1OCAzMCAzMCAzMHptMC00MGM1LjUxNCAwIDEwIDQuNDg2IDEwIDEwcy00LjQ4NiAxMC0xMCAxMC0xMC00LjQ4Ni0xMC0xMCA0LjQ4Ni0xMCAxMC0xMHoiLz48cGF0aCBkPSJtMjM2IDM5MmMxNi41NDIgMCAzMC0xMy40NTggMzAtMzBzLTEzLjQ1OC0zMC0zMC0zMC0zMCAxMy40NTgtMzAgMzAgMTMuNDU4IDMwIDMwIDMwem0wLTQwYzUuNTE0IDAgMTAgNC40ODYgMTAgMTBzLTQuNDg2IDEwLTEwIDEwLTEwLTQuNDg2LTEwLTEwIDQuNDg2LTEwIDEwLTEweiIvPjxwYXRoIGQ9Im0yMjYgMTMwdjE3MmMwIDUuNTIzIDQuNDc3IDEwIDEwIDEwczEwLTQuNDc3IDEwLTEwdi0xNzJjMC01LjUyMy00LjQ3Ny0xMC0xMC0xMHMtMTAgNC40NzctMTAgMTB6Ii8+PHBhdGggZD0ibTQxNiAyODBjMTYuODIyIDAgMzAtMTcuNTcgMzAtNDBzLTEzLjE3OC00MC0zMC00MC0zMCAxNy41Ny0zMCA0MCAxMy4xNzggNDAgMzAgNDB6bTAtNjBjNC4wNzcgMCAxMCA3Ljc5MSAxMCAyMHMtNS45MjMgMjAtMTAgMjAtMTAtNy43OTEtMTAtMjAgNS45MjMtMjAgMTAtMjB6Ii8+PGNpcmNsZSBjeD0iMjU2IiBjeT0iNDYyIiByPSIxMCIvPjxwYXRoIGQ9Im01MDIgNDEyaC0xNnYtMjgyYzAtNS41MjMtNC40NzctMTAtMTAtMTBoLTExMHYtMzBjMC01LjUyMy00LjQ3Ny0xMC0xMC0xMGgtNTB2LTcwYzAtNS41MjMtNC40NzctMTAtMTAtMTBoLTEyMGMtNS41MjMgMC0xMCA0LjQ3Ny0xMCAxMHY3My45MjNjLS43MTctLjI1Mi0xLjQ0Ny0uNDgyLTIuMTktLjY4MmwtMTkuMzItNS4xOGMtMTYuMDAzLTQuMjkyLTMyLjQ1OSA1LjE4Ni0zNi43MzggMjEuMjEtMS4yMiA0LjU1My03Ny4yMTEgMjg4LjE0MS03Ny42NSAyODkuNzgtMi4xNjIgOC4wNjYtLjgwNCAxNi4yNTkgMy4xMjMgMjIuOTQ5aC0yMy4yMjVjLTUuNTIzIDAtMTAgNC40NzctMTAgMTB2NDBjMCA1LjUyMyA0LjQ3NyAxMCAxMCAxMGg2NmM1LjUxNCAwIDEwIDQuNDg2IDEwIDEwcy00LjQ4NiAxMC0xMCAxMGMtNS41MjMgMC0xMCA0LjQ3Ny0xMCAxMHM0LjQ3NyAxMCAxMCAxMGMxNi41NDIgMCAzMC0xMy40NTggMzAtMzAgMC0zLjUwNi0uNjEtNi44Ny0xLjcyLTEwaDEwNi43MmM1LjUyMyAwIDEwLTQuNDc3IDEwLTEwcy00LjQ3Ny0xMC0xMC0xMGgtMTkxdi0yMGg0NzJ2MjBoLTE5MWMtNS41MjMgMC0xMCA0LjQ3Ny0xMCAxMHM0LjQ3NyAxMCAxMCA5Ljk5aDEwNi43MmMtMS4xMSAzLjE0LTEuNzIgNi41MDQtMS43MiAxMC4wMSAwIDE2LjU0MiAxMy40NTggMzAgMzAgMzAgNS41MjMgMCAxMC00LjQ3NyAxMC0xMHMtNC40NzctMTAtMTAtMTBjLTUuNTE0IDAtMTAtNC40ODYtMTAtMTBzNC40ODYtMTAgMTAtMTBoNjZjNS41MjMgMCAxMC00LjQ3NyAxMC0xMHYtNDBjMC01LjUyMy00LjQ3Ny0xMC0xMC0xMHptLTM5My4wNS0yMzkuOTMzIDM4LjYzNCAxMC4zNTQtMTAuMzUxIDM4LjYzMi0zOC42MzUtMTAuMzU0em0tMzMuMTQ1IDIzOS41ODMtMTkuMzE2LTUuMTY5Yy01LjMyNy0xLjQyOC04LjQ5OS02LjkyNC03LjA3LTEyLjI1Mmw0NC4wMDItMTY0LjIxMSAzOC42MzYgMTAuMzU0LTQzLjk5NSAxNjQuMjA1Yy0xLjM5NSA1LjE5OS02LjgwOCA4LjUyMy0xMi4yNTcgNy4wNzN6bTkwLjE5NS4zNWgtNTkuMzI1Yy4yNi0uNzM0LjQ5OS0xLjQ4LjcwNC0yLjI0Mmw1OC42MjEtMjE4Ljc5M3ptLS4yOTktMjk3LjE5OC0xMi45NDEgNDguMy0zOC42MzMtMTAuMzU0IDEyLjk0NS00OC4zMDljMS40MjEtNS4zMiA2Ljg4OS04LjQ5NCAxMi4yMzktNy4wNjFsMTkuMzE2IDUuMTc5YzUuNDQzIDEuNDYzIDguNDc4IDcuMDQxIDcuMDc0IDEyLjI0NXptMTIwLjI5OSAyOTcuMTk4aC0xMDBjMC00MC41NzUgMC0zOTEuNjc0IDAtMzkyaDEwMHptNjAgMGgtNDB2LTMxMmg0MHptMTIwIDBoLTEwMHYtOTJoMTAwem0wLTExMmgtMTAwdi0xMjBoMTAwem0wLTE0MGgtMTAwdi0yMGgxMDB6Ii8+PC9nPjwvZz48L3N2Zz4=',
			'supports'        => array( 'title', 'comments', 'author' ),
			'taxonomies'      => array_keys( $this->taxonomies ),
			'capability_type' => array( $this->single, $this->plural ),

		);


		$this->set_up_role_levels();

	}

	public function create_metaboxes() {
		// TODO: Implement create_metaboxes() method.
		$custom_taxonomies = Mooberry_Story_Community_Custom_Taxonomies_Settings::get_taxonomies();
		foreach ( $custom_taxonomies as $taxonomy ) {
				$single = isset( $taxonomy['singular_name'] ) ? sanitize_text_field( $taxonomy['singular_name'] ) : '';
			$plural = isset( $taxonomy['plural_name'] ) ? sanitize_text_field( $taxonomy['plural_name'] ) : '';
			$hierarchical = isset( $taxonomy['hierarchical']) && $taxonomy['hierarchical'] === 'yes';
			$multiple = isset( $taxonomy['multiple']) && $taxonomy['multiple'] === 'yes';

			$tax    = 'mbdsc_' . sanitize_title( $single );

			if ( !current_user_can( 'manage_' . $tax . '_terms')) {

				$metabox = new_cmb2_box( array(
						'id'           => $tax . '_taxonomy_metabox',
						'title'        => $single,
						'object_types' => array( $this->post_type, ),
						'context'      => 'side',
						'priority'     => 'high',
						'show_names'   => false,
					)
				);
				if ( $multiple ) {
					$type = $hierarchical ? 'taxonomy_multicheck_hierarchical' : 'taxonomy_multicheck';
				} else {
					$type = $hierarchical ? 'taxonomy_select_hierarchical' : 'taxonomy_select';
				}
				$metabox->add_field( array(
					'id'             => $tax,
					'type'           => $type,
					'taxonomy'       => $tax,
					'remove_default' => true,
					'text'           => array(
						'no_terms_text' => 'Sorry, no ' . strtolower( $plural ) . ' could be found.'
						// Change default text. Default: "No terms"
					),
					// Optionally override the args sent to the WordPress get_terms function.
					'query_args'     => array(
						'orderby' => 'slug',
						// 'hide_empty' => true,
					),

				) );

			}
		}
	}

	public function set_up_role_levels() {
		$single = $this->single;
		$plural = $this->plural;

		$this->reader_level = apply_filters( 'mbdsc_reader_level_capabilities', array() );

		$this->moderated_author_level = apply_filters( 'mbdsc_moderated_author_level_capabilities', array(
				'edit_' . $plural,
				'edit_' . $single,
				'delete_' . $plural,
				'delete_' . $single,
				'manage_' . $plural,
			)
		);

		$this->author_level = apply_filters( 'mbdsc_author_level_capabilities', array(
				'publish_' . $plural,
				'publish_' . $single,
				'edit_published_' . $single,
				'edit_published_' . $plural,
				'delete_published_' . $single,
				'delete_published_' . $plural,
				'upload_files',
				'manage_' . $plural,
				'read',
			)
		);

		$this->moderator_level = apply_filters( 'mbdsc_moderator_level_capabilities', array(
				'edit_others_' . $plural,
				'edit_others_' . $single,
				'delete_others_' . $plural,
				'delete_others_' . $single,
			)
		);

	}

}
