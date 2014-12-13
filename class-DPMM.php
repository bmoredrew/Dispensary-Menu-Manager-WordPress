<?php

/**
 * Main DPMM Class
 *
 * @class DPMM
 * @since 1.0
 */

class DPMM 
{
	public static $instance;

	public static function init()
	{
		if ( is_null( self::$instance ) )
			self::$instance = new DPMM();
		return self::$instance;
	}

	private function __construct()
	{
		add_shortcode( 'dpmm', array( $this, 'dpmm_shortcode' ) );
		add_action( 'init', array( $this, 'dpmm_frontend' ) );
		add_action( 'init', array( $this, 'dpmm_thumbnails') );
		add_action( 'init', array( $this, 'dpmm_post_type' ) );
		add_action( 'init', array( $this, 'dpmm_cat' ) );
		add_action( 'admin_head', array( $this, 'dpmm_cpt_cleanup' ) );
		add_action( 'admin_head', array( $this, 'dpmm_cpt_style' ) );

	}

	public function dpmm_frontend() 
	{
		wp_enqueue_style( 'dpmm-style', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'assets/css/style.css' );
	}

	public function dpmm_thumbnails() 
	{
		add_image_size( 'dpmm-thumb', 300, 300, true );
	}

	public function dpmm_shortcode( $atts )
	{

		extract( shortcode_atts( array(
	        'perpage' => 99
	    ), $atts ) );
	    
	    $output = '<div class="wm-wrap"><div class="wm-row wm-clearfix">';

	    $args = array(
	        'post_type' => 'dpmm_type',
	        'posts_per_page' => $perpage,
	        'sort_column'   => 'menu_order'
	    );

	    $mmjmenu_query = new  WP_Query( $args );
	    $count = $mmjmenu_query->post_count;
	    $i = 1;

	    while ( $mmjmenu_query->have_posts() ) : $mmjmenu_query->the_post();

	    	$thc_amt = get_post_meta(get_the_ID(), 'dpmm_thc-amt', true);
	    	$cbd_amt = get_post_meta(get_the_ID(), 'dpmm_cbd-amt', true);
	    	$strain_type = get_post_meta(get_the_ID(), 'dpmm_strain-type', true);

	        $output .= '<div class="wm-col-1-4" id="wm-item"><div class="wm-item-top">'
	                   . get_the_post_thumbnail(get_the_ID(),'dpmm-thumb')
	                   . '</div><div class="wm-item-info"><h2>'
	                   . get_the_title()
	                   . '</h2>'
	                   . '</div><div class="wm-item-content">'
	                   . '<h4>'
	                   . get_the_content()
	                   . '</h4>'
	                   . '<ul class="wm-item-strain-details">' 
	                   . '<li>THC:<br/>'
	                   . $thc_amt 
	                   . '</li><li>CBD:<br/>'
	                   . $cbd_amt
	                   . '</li><li>TYPE:<br/>'
	                   . $strain_type
	                   . '</li></ul>'
	                   . '</div>'
	                   . '</div>';

        if ( $i % 4 == 0 ) { $output .= '</div><div class="wm-row wm-clearfix">'; };

        $i++;

	    endwhile;
	    wp_reset_query();

	    $output .= '</div></div>';
	    return $output;

	}

	public function dpmm_post_type() 
	{

		$labels = array(
			'name'                => _x( 'Menu Items', 'Post Type General Name', 'text_domain' ),
			'singular_name'       => _x( 'Menu Item', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'           => __( 'Menu Manager', 'text_domain' ),
			'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
			'all_items'           => __( 'Menu Items', 'text_domain' ),
			'view_item'           => __( 'View Item', 'text_domain' ),
			'add_new_item'        => __( 'Add New Menu Item', 'text_domain' ),
			'add_new'             => __( 'Add Menu Item', 'text_domain' ),
			'edit_item'           => __( 'Edit Item', 'text_domain' ),
			'update_item'         => __( 'Update Item', 'text_domain' ),
			'search_items'        => __( 'Search Item', 'text_domain' ),
			'not_found'           => __( 'Not found', 'text_domain' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
		);
		$rewrite = array(
			'slug'                => 'menu-item',
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		);
		$args = array(
			'label'               => __( 'dpmm_type', 'text_domain' ),
			'description'         => __( 'Post Type Description', 'text_domain' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', ),
			'taxonomies'          => array( 'dpmm_menus' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => false,
			'menu_position'       => 25,
			'menu_icon'           => plugins_url('assets/img/',  __FILE__) . 'icon.png',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capability_type'     => 'page',
		);
		register_post_type( 'dpmm_type', $args );

	}

	public function dpmm_cat() 
	{

		$labels = array(
			'name'                       => _x( 'Menu Categories', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Menu Category', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Menu Categories', 'text_domain' ),
			'all_items'                  => __( 'All Items', 'text_domain' ),
			'parent_item'                => __( 'Parent Item', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
			'new_item_name'              => __( 'New Item Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New Item', 'text_domain' ),
			'edit_item'                  => __( 'Edit Item', 'text_domain' ),
			'update_item'                => __( 'Update Item', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
			'search_items'               => __( 'Search Items', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from the most used items', 'text_domain' ),
			'not_found'                  => __( 'Not Found', 'text_domain' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
		);
		register_taxonomy( 'dpmm_cat', array( 'dpmm_type' ), $args );

	}

	public function dpmm_cpt_cleanup() 
	{
	    global $submenu;

	    unset($submenu['edit.php?post_type=dpmm_type'][10]);
	    
		if('dpmm_type' == get_post_type())
	  	    echo '';
	}
	public function dpmm_cpt_style() 
	{
		echo '<style>#menu-posts-dpmm_type .wp-menu-image img { padding: 0; }</style>';
	}


}

DPMM::init();