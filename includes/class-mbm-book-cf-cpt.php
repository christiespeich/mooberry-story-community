<?php


class Mooberry_Book_Manager_Book_CF_CPT extends Mooberry_Book_Manager_Book_CPT {


    public function __construct() {
	    parent::__construct();

	    add_action( 'admin_head', array( $this, 'add_css_for_custom_taxonomies' ) );
	    add_action( 'wp_head', array( $this, 'add_css_for_custom_taxonomies_book_page' ) );
    }

    public function add_css_for_custom_taxonomies() {
	    $custom_taxonomies = $this->get_custom_taxonomies() ;

        $output = '<style type="text/css">';
        foreach ( $custom_taxonomies  as $slug ) {
	        $output .= " #new{$slug}_parent, .taxonomy-{$slug} #parent, .taxonomy-{$slug} label[for=parent],";
        }
        $output = rtrim( $output, "," );
        $output .= ' {
                    display: none;
                }
            </style>';
        echo $output;
	}

	public function add_css_for_custom_taxonomies_book_page() {
	   $custom_taxonomies = $this->get_custom_taxonomies() ;

        $output = '<style type="text/css">';
        foreach ( $custom_taxonomies  as $slug ) {

	        $output   .= " #mbm-book-page .mbm-book-details-{$slug}-label,";
        }
        $output = rtrim( $output, ',' );
        $output .= ' {
                font-weight: bold;
                }
                </style>';
        echo $output;
	}

	public function get_custom_taxonomies() {
        return array_diff_key( array_keys( $this->taxonomies ), $this->standard_taxonomies );
	}


	protected function get_taxonomies_on_top() {

        $taxonomies = array();

		$custom_taxonomies = $this->get_custom_taxonomies();
		foreach ( $custom_taxonomies as $custom_taxonomy ) {
			$taxonomies[] = 'tagsdiv-' . $custom_taxonomy;
		}

		return array_merge( $taxonomies ,  parent::get_taxonomies_on_top() );
	}




}