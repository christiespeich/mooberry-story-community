<?php

class MBDSP_Custom_Fields_Settings {


	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	private $key = 'mbdsp_options';

	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	private $metabox_id = 'mbdsp_option_metabox';

	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Holds an instance of the object
	 *
	 * @var Myprefix_Admin
	 **/
	//private static $instance = null;

	/**
	 * Constructor
	 * @since 1.1
	 */
	public function __construct() {
		// Set our title
		$this->title = __( 'Mooberry Story Custom Fields', 'mooberry-story-premium' );

		add_action( 'update_option_mbdsp_options', array($this, 'options_updated'), 10, 2 );
		add_action( 'admin_init', array($this, 'init' ) );
		add_action( 'admin_menu', array($this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init', array($this, 'add_options_page_metabox' ) );


	}

	/**
	 * Returns the running object
	 *
	 * @return Myprefix_Admin
	 **/
/*	public static function get_instance() {
		if( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->hooks();
		}
		return self::$instance;
	}
*/
	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	 /*
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );
	}
*/

	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {

			$permissions = 'manage_options';
		$sub_page_hook = add_submenu_page( 'mbdb_options', $this->title, __('Custom Fields', 'mooberry-story-premium'), $permissions, 'mbdsp_custom_fields', array( $this, 'admin_page_display') );

		add_action( "admin_print_styles-{$sub_page_hook}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );

	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {

		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );

		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );

		// Set our CMB2 fields
	/*	$cmb->add_field(array(
				'id'	=> 'general_settings_title',
				'name'	=>	__('GENERAL SETTINGS', 'mooberry-story-premium'),
				'type'	=>	'title',
			)
		);*/

	/*	$cmb->add_field(array(
			'id'	=>	'affiliate_code_scope',
			'name'	=>	__('Allow each author to enter their own affiliate codes', 'mooberry-story-premium'),
			'type'	=>	'checkbox',
			)
		);*/



		$cmb->add_field(array(
				'id'	=> 'author_page_title',
				'name'	=>	__('TAXONOMY FIELDS', 'mooberry-story-premium'),
				'type'	=>	'title',
			)
		);

		$cmb->add_field(array(
				'id'          => 'taxonomies',
				'type'        => 'group',
				'desc'			=>	__('Taxonomies organize books. Adding a Taxonomy Field will create a new field that works like Genres, Series, Tags, etc.', 'mooberry-book-manager'),
				'options'     => array(
					'group_title'   => __('Taxonomy Field', 'mooberry-book-manager') . ' {#}',  // since version 1.1.4, {#} gets replaced by row number
					'add_button'    =>  __('Add New Taxonomy Field', 'mooberry-book-manager'),
					'remove_button' =>  __('Remove Taxonomy Field', 'mooberry-book-manager') ,
					'sortable'      => false, // beta
				),
			)
		);

		$cmb->add_group_field( 'taxonomies', array(
				'name' => __('Singular Name', 'mooberry-book-manager'),
				'id'   => 'singular_name',
				'type' => 'text_medium',
				'attributes' => array(
					'required' => 'required',
				),
			)
		);

		$cmb->add_group_field( 'taxonomies', array(
				'name' => __('Plural Name', 'mooberry-book-manager'),
				'id'   => 'plural_name',
				'type' => 'text_medium',
				'attributes' => array(
					'required' => 'required',
				),
			)
		);

		$cmb->add_group_field( 'taxonomies', array(
				'name' => __('Taxonomy Grid Heading', 'mooberry-book-manager'),
				'id'   => 'heading',
				'desc'  => __( 'This will be the title on the Taxonomy Grid page. You can use %s where you want the term.', 'mooberry-book-manager'),
				'type' => 'text_medium',
				'attributes' => array(
					'required' => 'required',
				),
			)
		);








	}

	/**
	 * Register settings notices for display
	 *
	 * @since  0.1.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}

		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'mooberry-story-premium' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}

	/**
	 *
	 *  Register all taxonomies
     *  Flush rewrite rules
	 *  This function runs if ANY of the fields were updated.
	 *
	 *
	 *  @since 3.0
	 *  @param [string] $old_value
	 *  @param [string] $new_value
	 *
	 *  @access public
	 */
	public function options_updated( $old_value, $new_value ) {

        $options = get_option('mbdb_options');

	    if ( array_key_exists( 'taxonomies', $new_value ) ) {
	       MBDSP()->register_taxonomies( $new_value[ 'taxonomies' ] );
	       $single = isset( $new_value['singular_name'] ) ? sanitize_text_field( $new_value['singular_name'] ) : '';
			$tax    = 'mbdb_' . sanitize_title( $single );

	       // if any don't have slugs set, do it now
            if ( in_array( $single, mbdb_wp_reserved_terms() ) ) {
					$single = 'book-' . $single;
			}
            $options['mbdb_book_grid_' . $tax . '_slug'] = $single;

        }
        update_option( 'mbdb_options', $options );
	    flush_rewrite_rules();
	    global $wp_rewrite;
	    $wp_rewrite->flush_rules();


	/*
		$flush = false;

		// if tax grid page changes, flush rewrite rules
		$key = 'mbdb_tax_grid_page';
		if ( (!array_key_exists($key, $old_value)) || ($old_value[$key] != $new_value[$key]) ) {
			$flush = true;
		} else {
			// if any of the tax slugs change, flush the rewrite rules
			//$taxonomies = MBDB()->book_CPT->taxonomies;
			$taxonomies = get_object_taxonomies('mbdb_book', 'objects' );
			foreach($taxonomies as $name => $taxonomy) {
				$key = 'mbdb_book_grid_' . $name . '_slug';
				if ( (!array_key_exists($key, $old_value)) || ($old_value[$key] != $new_value[$key]) ) {

					$flush = true;
					break;
				}
			}
		}
		if ( $flush ) {
			flush_rewrite_rules();
		}*/
	}


}

