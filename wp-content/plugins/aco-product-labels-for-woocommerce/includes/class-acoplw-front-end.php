<?php

if (!defined('ABSPATH'))
    exit;

class ACOPLW_Front_End
{

    static $cart_error = array();
    /**
     * The single instance of WordPress_Plugin_Template_Settings.
     * @var    object
     * @access  private
     * @since    1.0.0
     */
    private static $_instance = null;
    public $products = false;
    /**
     * The version number.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_version;
    /**
     * The token.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_token;
    /**
     * The plugin assets URL.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $assets_url;
    /**
     * The main plugin file.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $file;
    
    private $badge;

    /**
     * Check if price has to be display in cart and checkout
     * @var type
     * @var boolean
     * @access private
     * @since 3.4.2
     */
    private $show_price = false;

    function __construct($badge, $file = '', $version = '1.0.0')
    {

        $this->_version = $version;
        $this->_token = ACOPLW_TOKEN;
        $this->badge = $badge;
        add_action('init', array($this, 'register_acoplw_post_types'));

        if ($this->acoplw_check_woocommerce_active()) {

            // Enqueue Scripts
            add_action('wp_enqueue_scripts', array ( $this, 'enqueue_styles' ), 10);
            add_action('wp_enqueue_scripts', array ( $this, 'enqueue_scripts' ), 10);

            // Custom Styles
            add_action('wp_footer', array ( $this, 'customStyles' ) );
            
            // Badge
            add_filter( 'woocommerce_single_product_image_html', array( $this, 'acoplwBadge' ), 100000, 2 );
            add_filter( 'post_thumbnail_html', array( $this, 'acoplwBadge' ), 100000, 2 );
            add_filter( 'woocommerce_product_get_image', array( $this, 'acoplwBadge' ), 100000, 2 );
            // add_filter( 'woocommerce_single_product_image_thumbnail_html', array( $this, 'acoplwBadge' ), 99, 2 );

            // Sidebadr
			// add_action( 'dynamic_sidebar_before', array( $this, 'acoplwSidebarBadge' ) );
			// add_action( 'dynamic_sidebar_after', array( $this, 'acoplwSidebarBadge' ) );

			// // Minicart
			// add_action( 'woocommerce_before_mini_cart', array( $this, 'acoplwMinicartBadge' ) );
            // add_action( 'woocommerce_after_mini_cart', array( $this, 'acoplwMinicartBadge' ) );
            
            // edit sale flash badge.
            // add_filter( 'woocommerce_sale_flash', array( $this, 'acoplwSaleBadge' ), 99, 1 );
            $title_hook = get_option('acoplw_enable_title_hook') ? get_option('acoplw_enable_title_hook') : 0;               
            if ( $title_hook ) {
                add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'acoplwTitleHook' ), 9999, 3 );
            }

            // Detail Page Badge
            $badgeDetail = get_option('acoplw_detail_page_badge') ? get_option('acoplw_detail_page_badge') : 0; 

            if ($badgeDetail) { 
                add_action( 'woocommerce_before_template_part', array ( $this, 'acoplwBadgeDetail' ), 100, 4 );
            }
            
        }

    }

    /**
     * Load frontend CSS.
     * @access  public
     * @since   1.0.0
     * @return void
     */
    public function enqueue_styles()
    {

        wp_register_style('acoplw-style', plugin_dir_url( __FILE__ ) . '../assets/css/frontend.css', array(), $this->_version);

        wp_enqueue_style('acoplw-style');

    }

    public function customStyles()
    {

        echo $this->badge->customStyles();

    }

    /**
     * ACOPLW Badges
     * @param $productThumb, $product
     */

    public function acoplwBadge ( $productThumb, $product = false )
    {

        if ( !is_admin() && !is_single() ) {

            return $this->badge->acoplwBadge( $productThumb, $product, false );

        } else {

            return $productThumb;
            
        }

    }

    /**
    * ACOPLW Badges Detail
    * @param $productThumb, $product
    */

    public function acoplwBadgeDetail ( $template_name, $template_path, $located, $args ) {
	
		if ($template_name == 'single-product/product-image.php') { 
			
            $this->badge->acoplwBadgeDetail ();

		}
		
	}

    /**
     * ACOPLW Badges
     * @param $productImageHTML, $thumbID 
     */

    public function acoplwTitleHook ()
    {

        if ( !is_admin() && !is_single() ) {

            global $product;
            $productThumb = '';
            echo $this->badge->acoplwBadge( $productThumb, $product, true );

        } 

    }

    
    /**
     * Load frontend JS.
     * @access  public
     * @since   1.0.0
     * @return void
     */
    public function enqueue_scripts()
    {

        wp_register_script('acoplw-script', plugin_dir_url( __FILE__ ) . '../assets/js/frontend.js', array('jquery'), $this->_version);

        wp_enqueue_script('acoplw-script');

    }

    // /**
    //  * ACOPLW Badges
    //  * @param $productImageHTML, $thumbID 
    //  */

    // public function acoplwSaleBadge ( $badge )
    // {
    //     if ( !is_admin() && !is_single() ) {
    //         return $this->badge->acoplwSaleBadge( $badge );
    //     } else {
    //         return $badge;
    //     }
    // }

    /**
     * ACOPLW Badges
     * @param $productImageHTML, $thumbID 
     */

    public function acoplwBadgeThumbnail ( $productImageHTML, $thumbID )
    {

        return $productImageHTML;

    }

    /**
     * ACOPLW Badges
     * @param $productImageHTML, $thumbID 
     */

    public function acoplwSidebarBadge ( $productImageHTML, $thumbID )
    {

        return $productImageHTML;

    }

    /**
     * ACOPLW Badges
     * @param $productImageHTML, $thumbID 
     */

    public function acoplwMinicartBadge ( $productImageHTML, $thumbID )
    {

        return $productImageHTML;

    }

    /**
     * Check if woocommerce plugin is active
     */
    public function acoplw_check_woocommerce_active()
    {

        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            return true;
        }
        if (is_multisite()) {
            $plugins = get_site_option('active_sitewide_plugins');
            if (isset($plugins['woocommerce/woocommerce.php']))
                return true;
        }
        return false;

    }

    /**
     * ACOPLW Register Post Types
     */
    public function register_acoplw_post_types()
    {

        $post_type = ACOPLW_POST_TYPE;
        $labels = array(
            'name' => __('Badges', 'aco-product-labels-for-woocommerce'),
            'singular_name' => __('Badge', 'aco-product-labels-for-woocommerce'),
            'name_admin_bar' => 'PLW_Badge',
            'add_new' => _x('Add New Product Badge', $post_type, 'aco-product-labels-for-woocommerce'),
            'add_new_item' => __('Add New Badge', 'aco-product-labels-for-woocommerce'),
            'edit_item' => __('Edit Badge', 'aco-product-labels-for-woocommerce'),
            'new_item' => __('New Badge', 'aco-product-labels-for-woocommerce'),
            'all_items' => __('Badges', 'aco-product-labels-for-woocommerce'),
            'view_item' => __('View Badge', 'aco-product-labels-for-woocommerce'),
            'search_items' => __('Search Badge', 'aco-product-labels-for-woocommerce'),
            'not_found' => __('No Badge Found', 'aco-product-labels-for-woocommerce'),
            'not_found_in_trash' => __('No Badge Found In Trash', 'aco-product-labels-for-woocommerce'),
            'parent_item_colon' => __('Parent Badge'),
            'menu_name' => 'Custom Product Options'
        );
        $args = array(
            'labels' => apply_filters($post_type . '_labels', $labels),
            'description' => '',
            'public' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'show_ui' => false,
            // 'show_in_menu' => 'edit.php?post_type=product',
            'show_in_nav_menus' => false,
            'query_var' => false,
            'can_export' => true,
            'rewrite' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'rest_base' => $post_type,
            'hierarchical' => false,
            'show_in_rest' => false,
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'supports' => array('title'),
            'menu_position' => 5,
            'menu_icon' => 'dashicons-admin-post',
            'taxonomies' => array('product_cat')
        );
        register_post_type($post_type, apply_filters($post_type . '_register_args', $args, $post_type));

        // Product Lists
        $post_type = ACOPLW_PRODUCT_LIST;
        $labels = array(
            'name' => __('Product Lists', 'aco-product-labels-for-woocommerce'),
            'singular_name' => __('Product List', 'aco-product-labels-for-woocommerce'),
            'name_admin_bar' => 'PLW_Badge',
            'add_new' => _x('Add New Product List', $post_type, 'aco-product-labels-for-woocommerce'),
            'add_new_item' => __('Add New List', 'aco-product-labels-for-woocommerce'),
            'edit_item' => __('Edit List', 'aco-product-labels-for-woocommerce'),
            'new_item' => __('New List', 'aco-product-labels-for-woocommerce'),
            'all_items' => __('Product Lists', 'aco-product-labels-for-woocommerce'),
            'view_item' => __('View List', 'aco-product-labels-for-woocommerce'),
            'search_items' => __('Search List', 'aco-product-labels-for-woocommerce'),
            'not_found' => __('No List Found', 'aco-product-labels-for-woocommerce'),
            'not_found_in_trash' => __('No List Found In Trash', 'aco-product-labels-for-woocommerce'),
            'parent_item_colon' => __('Parent List'),
            'menu_name' => 'Custom Product Options'
        );
        $args = array(
            'labels' => apply_filters($post_type . '_labels', $labels),
            'description' => '',
            'public' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'show_ui' => false,
            // 'show_in_menu' => 'edit.php?post_type=product',
            'show_in_nav_menus' => false,
            'query_var' => false,
            'can_export' => true,
            'rewrite' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'rest_base' => $post_type,
            'hierarchical' => false,
            'show_in_rest' => false,
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'supports' => array('title'),
            'menu_position' => 5,
            'menu_icon' => 'dashicons-admin-post',
            'taxonomies' => array('product_cat')
        );
        register_post_type($post_type, apply_filters($post_type . '_register_args', $args, $post_type));

    }

}
