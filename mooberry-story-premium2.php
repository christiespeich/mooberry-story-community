<?php

	define('MBDSC_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
	define('MBDSC_PLUGIN_VERSION_KEY', 'mbdsc_version');
	define('MBDSC_PLUGIN_VERSION', '0.1');



// Plugin Folder URL
if ( ! defined( 'MBDSC_PLUGIN_URL' ) ) {
	define( 'MBDSC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Plugin Root File
if ( ! defined( 'MBDSC_PLUGIN_FILE' ) ) {
	define( 'MBDSC_PLUGIN_FILE', __FILE__ );
}






if ( ! class_exists( 'Mooberry_Story_Community' ) ) :

 final class Mooberry_Story_Community  {
	 /** Singleton *************************************************************/

	/**
	 * @var Mooberry_Story_Community The one true Mooberry_Story_Community
	 * @since 3.0
	 */
	private static $instance;
	protected $settings;



	/**
	 * Main Mooberry_Book_Manager_Multi_author Instance
	 *
	 * Insures that only one instance of Mooberry_Story_Community exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 1.0
	 * @since 1.1 Added Settings
	 * @static
	 * @staticvar array $instance
	 *
	 * @see MBDB()
	 * @return  Mooberry_Story_Community The one true Mooberry_Story_Community
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Mooberry_Story_Community ) ) {


			self::$instance = new Mooberry_Story_Community;
			self::$instance->settings = new Mooberry_Story_Custom_Fields_Settings();



/*
			MBDB()->book_CPT->add_taxonomy( new Mooberry_Book_Manager_Taxonomy( 'mbdb_length', 'mbdb_book', 'Length', 'Lengths', array('meta_box_cb' => 'post_categories_meta_box',
				'capabilities' => array(
					'manage_terms' => 'manage_genre_terms', //'manage_categories',
					'edit_terms'   => 'manage_genre_terms', //'manage_categories',
					'delete_terms' => 'manage_genre_terms',
					'assign_terms' => 'assign_genre_terms',
				))));*/

			$taxonomies = get_option('mbdsc_options', array());
			if ( is_array($taxonomies) && array_key_exists( 'taxonomies', $taxonomies ) ) {
				self::$instance->register_taxonomies( $taxonomies['taxonomies'] );
			}



		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 3.0
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', MBDSC_PLUGIN_VERSION );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 3.0
	 * @access protected
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__,  'Cheatin&#8217; huh?', MBDSC_PLUGIN_VERSION );
	}

	public function register_taxonomies( $taxonomies ) {
		foreach ( $taxonomies as $taxonomy ) {
			$single = isset( $taxonomy['singular_name'] ) ? sanitize_text_field( $taxonomy['singular_name'] ) : '';
			$plural = isset( $taxonomy['plural_name'] ) ? sanitize_text_field( $taxonomy['plural_name'] ) : '';
			$tax    = 'mbs_' . sanitize_title( $single );

			MBDB()->book_CPT->add_taxonomy( new Mooberry_Book_Manager_Taxonomy( $tax, 'mbdb_book', $single, $plural, array(
				'meta_box_cb'  => 'post_categories_meta_box',
				'capabilities' => array(
					'manage_terms' => 'manage_genre_terms', //'manage_categories',
					'edit_terms'   => 'manage_genre_terms', //'manage_categories',
					'delete_terms' => 'manage_genre_terms',
					'assign_terms' => 'assign_genre_terms',
				)
			) ) );

		}
	}

}

endif; // End if class_exists check



/**
 * The main function responsible for returning the one true Mooberry Book Manager Custom Fields
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $mbdsc = MBDSC(); ?>
 *
 * @since 0.2
 * @return object The one true Mooberry_Story_Community Instance
 */
function MBDSC() {
	return Mooberry_Story_Community::instance();
}

function mbdsc_start() {
	MBDSC();
}


// set priority to 30 to ensure it runs after MBM's plugins_loaded
	add_action( 'plugins_loaded', 'mbdsc_plugins_loaded', 30 );
	function mbdsc_plugins_loaded() {


		//require_once( MBDSC_PLUGIN_DIR . '/includes/class-mbm-book-cf-cpt.php');
		require_once( MBDSC_PLUGIN_DIR . '/includes/admin/class-custom-fields-settings.php');
		//$mbdsc_edd_updated = new MBDB_License(__FILE__, 'Custom Fields', MBDSC_PLUGIN_VERSION );

		load_plugin_textdomain( 'mooberry-story-premium', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		mbdsc_start();
	}

	// deactivates the plugin
	function mbdsc_deactivate_custom_fields() {
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}

	// what to do when the plugin is deactivated
	register_deactivation_hook( __FILE__, 'mbdsc_deactivate' );
	function mbdsc_deactivate( $networkwide ) {
		global $blog_id;

		if (function_exists('is_multisite') && is_multisite()) {
			// check if it is a network activation - if so, run the activation function for each blog id
			if ( $networkwide ) {
				$old_blog = $blog_id;
				// Get all blog ids
				global $wpdb;
				$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
				foreach ($blogids as $blog) {
					switch_to_blog($blog);
					if (!wp_is_large_network() ) {
						delete_blog_option( $blog, 'rewrite_rules' );
					}
				}
				 switch_to_blog($old_blog);
				return;
			}
		}
		flush_rewrite_rules();
	}

  // v0.4 make multi-site compatible
	register_activation_hook( MBDSC_PLUGIN_FILE, 'mbdsc_activate' );
	function mbdsc_activate( $networkwide ) {

		global $blog_id;
		global $wpdb;

		//require_once( MBDSC_PLUGIN_DIR . '/includes/class-mbm-book-cf-cpt.php');
		require_once( MBDSC_PLUGIN_DIR . '/includes/admin/class-custom-fields-settings.php');

		// This includes the class files and starts the object
		// run this in activation because plugins_loaded hasn't run yet
		mbdsc_start();




		// this is per-site
		if (function_exists('is_multisite') && is_multisite()) {
			// check if it is a network activation - if so, run the activation function for each blog id
			if ( $networkwide ) {
				$old_blog = $blog_id;
				// Get all blog ids
				$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

				foreach ($blogids as $blog) {
					switch_to_blog($blog);
					_mbdsc_activate( $blog );

					if (!wp_is_large_network() ) {
						delete_blog_option( $blog, 'rewrite_rules' );
					}
				}
				switch_to_blog($old_blog);
				return;
			}
		}
		_mbdsc_activate();
		flush_rewrite_rules();
	}

	function _mbdsc_activate( $blog = 1) {


	}

// activate MBM MA for any new blogs added to multisite
// v0.4
add_action( 'wpmu_new_blog', 'mbdsc_new_blog' );
function mbdsc_new_blog($blog ) {
	//wp_die('Network Activation Not Supported.');

	global $blog_id;

    if (is_plugin_active_for_network('mooberry-story-premium/mooberry-story-premium.php')) {
        $old_blog = $blog_id;
        switch_to_blog($blog);

        _mbdsc_activate($blog);
		delete_blog_option( $blog, 'rewrite_rules' );
        switch_to_blog($old_blog);
    }

}


/*
add_filter( 'mbdb_extra_book_details', 'mbdb_custom_tax_display');
function mbdb_custom_tax_display( $content ) {
	global $post;
	$book_cpt = MBDB()->book_CPT;
	$book_id  = $post->ID;

	$custom_taxonomies = $book_cpt->get_custom_taxonomies();
	foreach ( $custom_taxonomies as $custom_taxonomy ) {
		$tax  = $book_cpt->taxonomies[ $custom_taxonomy ];
		$terms = get_the_terms( $book_id, $custom_taxonomy );
		if (  $terms  === false ) {
			continue;
		}
		$attr = array( 'delim' => "comma", 'blank' => "", 'book' => $post->post_title );

		$permalink = MBDB()->options->get_tax_grid_slug( $custom_taxonomy );

		$value = wp_get_object_terms( $book_id, $custom_taxonomy );

		$content .= '<span class="mbm-book-details-' . $tax->slug . '-label">' . $tax->plural_name. ':</span> <span class="mbm-book-details-' . $tax->slug . '-data">';
		$content .= $book_cpt->output_taxonomy( 'mbm-book-' .  $tax->slug , $value, $permalink, $custom_taxonomy, $attr );
		$content .= '</span><br/>';


	}

	return $content;
}*/

//add_action( 'admin_head', 'mbdsc_add_css_for_custom_taxonomies' );
function mbdsc_add_css_for_custom_taxonomies() {
    $custom_taxonomies = MBDB()->book_CPT->get_custom_taxonomies() ;

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

//add_action( 'wp_head', 'mbdsc_add_css_for_custom_taxonomies_book_page' );
function mbdsc_add_css_for_custom_taxonomies_book_page() {
   $custom_taxonomies = MBDB()->book_CPT->get_custom_taxonomies() ;

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

add_filter( 'mbdb_tax_grid_title', 'mbdsc_custom_tax_grid_title' );
function mbdsc_custom_tax_grid_title( $title ) {
	global $wp_query;
	if ( isset( $wp_query->query_vars['the-term'] ) ) {
		$mbdb_term = trim( urldecode( $wp_query->query_vars['the-term'] ), '/' );
		if ( isset( $wp_query->query_vars['the-taxonomy'] ) ) {
			$mbdb_taxonomy = trim( urldecode( $wp_query->query_vars['the-taxonomy'] ), '/' );
			$term          = get_term_by( 'slug', $mbdb_term, $mbdb_taxonomy );
			$taxonomy      = get_taxonomy( $mbdb_taxonomy );
			if ( isset( $term ) && isset( $taxonomy ) && $term != null && $taxonomy != null ) {
				if ( in_array( $mbdb_taxonomy, MBDB()->book_CPT->get_custom_taxonomies() ) ) {
					$taxonomies = get_option( 'mbdsc_options' );
					if ( array_key_exists( 'taxonomies', $taxonomies ) ) {
						$taxonomy_options = array();
						foreach ( $taxonomies['taxonomies'] as $tax_data ) {
							$taxonomy_options[ 'mbdb_' . sanitize_title( $tax_data['singular_name'] ) ] = $tax_data['heading'];
						}
						//$taxonomy_options = array_column( $taxonomies['taxonomies'], 'heading', 'singular_name' );
						if ( array_key_exists( $mbdb_taxonomy, $taxonomy_options ) ) {
							$title = ucfirst(sprintf($taxonomy_options[ $mbdb_taxonomy ], $term->name ));
						}
					}
				}

			}
		}
	}

	return $title;

}



