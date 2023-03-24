<?php
/*This file is part of astra-child, astra child theme.

All functions of this file will be loaded before of parent theme functions.
Learn more at https://codex.wordpress.org/Child_Themes.

Note: this function loads the parent stylesheet before, then child theme stylesheet
(leave it in place unless you know what you are doing.)
*/

if ( ! function_exists( 'suffice_child_enqueue_child_styles' ) ) {
	function astra_child_enqueue_child_styles() {
	    // loading parent style
	    wp_register_style(
	      'parente2-style',
	      get_template_directory_uri() . '/style.css'
	    );

	    wp_enqueue_style( 'parente2-style' );
	    // loading child style
	    wp_register_style(
	      'childe2-style',
	      get_stylesheet_directory_uri() . '/style.css'
	    );
	    wp_enqueue_style( 'childe2-style');
	 }
}
add_action( 'wp_enqueue_scripts', 'astra_child_enqueue_child_styles' );

/*Write here your own functions */


function getcat(){

		 $id = 22;
if( $term = get_term_by( 'id', $id, 'product_cat' ) ){
	?>
	 
											 <a href="<?php echo get_category_link($term->term_id); ?>">
                                             <p>   <?php echo $term->name;  ?></p>
												<?php
$thumbnail_id = get_woocommerce_term_meta( $id, 'thumbnail_id', true );
$image = wp_get_attachment_url( $thumbnail_id );
echo '<img src="'.$image.'" alt="" width="" height="" />';
?>
<button type="button">Bekijk de hele collectie</button>

												</a> 
											  <?php
											    //  $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true ); 								 
									?>		 
	<?php 
}
}

add_shortcode('getcatdata', 'getcat');
function getcat11(){
		$args = array(
        'taxonomy' => 'product_cat',
        'orderby' => 'name',
        'field' => 'name',
        'order' => 'ASC',
        'hide_empty' => false,
		'number' => 6,
		 'exclude' => array( 15 ),
		
    );
    $all_cats = get_categories($args);
	echo '<ul class="mainrow">';
    foreach ($all_cats as $cat){
     $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
     $image = wp_get_attachment_url( $thumbnail_id );
?>
<li class="lirowcls">
<a href="<?php echo get_category_link($cat->term_id); ?>"><p>   <?php echo $cat->name;  ?></p><img src="<?php echo $image; ?>" alt="" width="" height="" /><button type="button">Bekijk de hele collectie</button></a></li>
<?php
    }
	echo '</ul>';
}

add_shortcode('getcatdata1', 'getcat11');



//  woocommerce custom code Start

function csp_locate_template($template, $template_name, $template_path){
	
 $basename = basename($template);
 if ($basename == 'cart.php')    {
 $template = trailingslashit(plugin_dir_path(__FILE__)) . '/woocommerce/templates/cart/cart.php';
 }
 
 
  $basename = basename($template);
 if ($basename == 'product-searchform.php')    {
 $template = trailingslashit(plugin_dir_path(__FILE__)) . '/woocommerce/templates/product-searchform.php';
 }
 
   $basename = basename($template);
 if ($basename == 'form-tracking.php')    {
 $template = trailingslashit(plugin_dir_path(__FILE__)) . '/woocommerce/templates/order/form-tracking.php';
 }
 
    $basename = basename($template);
 if ($basename == 'related.php')    {
 $template = trailingslashit(plugin_dir_path(__FILE__)) . '/woocommerce/templates/single-product/related.php';
 }
 
   $basename = basename($template);
 if ($basename == 'description.php')    {
 $template = trailingslashit(plugin_dir_path(__FILE__)) . '/woocommerce/templates/single-product/tabs/description.php';
 }
 
    $basename = basename($template);
 if ($basename == 'cart-totals.php')    {
 $template = trailingslashit(plugin_dir_path(__FILE__)) . '/woocommerce/templates/cart/cart-totals.php';
 }
 
 
 return $template;
 }
 
 add_filter( 'woocommerce_locate_template', 'csp_locate_template', 10, 3 );

/* add_action('woocommerce_after_shop_loop_item_title','woocommerce_template_single_excerpt', 5);
function woocommerce_template_single_excerpt() {
            global $product;
            if ($product->product_type == "variable" && (is_shop() )) {
              echo woocommerce_variable_product(); 
            }

 } */
 add_filter('woocommerce_dropdown_variation_attribute_options_args','fun_select_default_option',10,1);
function fun_select_default_option( $args)
{

    if(count($args['options']) > 0) //Check the count of available options in dropdown
        $args['selected'] = $args['options'][0];
    return $args;
}

add_action( 'woocommerce_after_add_to_cart_button', 'bbloomer_echo_short_desc_if_empty', 21 );



function action_woocommerce_product_meta_end1(  ) { 
  return dynamic_sidebar( 'astra-woo-single-sidebar' );
	
};    



    
// add the action 
add_action( 'woocommerce_product_meta_end', 'action_woocommerce_product_meta_end1', 10, 0 ); 
 
function bbloomer_echo_short_desc_if_empty() {
   global $post;
   echo $post->post_excerpt;
  // echo do_shortcode('[woo-related]');
}

 
function wc_custom_single_addtocart_text() {
    return "→";
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'wc_custom_single_addtocart_text' );
 
 
 
 
 function getcat12(){
		$args = array(
        'taxonomy' => 'product_cat',
        'orderby' => 'name',
        'field' => 'name',
        'order' => 'ASC',
        'hide_empty' => false,
		'number' => 1,
		 'exclude' => array( 15 ),
		
    );
    $all_cats = get_categories($args);
	echo '<ul class="mainrow">';
    foreach ($all_cats as $cat){
     $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
     $image = wp_get_attachment_url( $thumbnail_id );
?>
<li class="lirowcls">
<a href="<?php echo get_category_link($cat->term_id); ?>"><p>   <?php echo $cat->name;  ?></p><img src="<?php echo $image; ?>" alt="" width="" height="" /><button type="button">Bekijk de hele collectie</button></a></li>
<?php
    }
	echo '</ul>';
}

add_shortcode('getcatdata12', 'getcat12');
 
 
 
 
 function getcat112(){

		 $id = 38;
if( $term = get_term_by( 'id', $id, 'product_cat' ) ){
	?>
	 
											 <a href="<?php echo get_category_link($term->term_id); ?>">
                                             <p>   <?php echo $term->name;  ?></p>
												<?php
$thumbnail_id = get_woocommerce_term_meta( $id, 'thumbnail_id', true );
$image = wp_get_attachment_url( $thumbnail_id );
echo '<img src="'.$image.'" alt="" width="" height="" />';
?>
<button type="button">Bekijk de hele collectie</button>

												</a> 
											  <?php
											    //  $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true ); 								 
									?>		 
	<?php 
}
}

add_shortcode('getcatdata11', 'getcat112');
 ?>
 
 <?php
 function woocommerce_button_proceed_to_checkout() { ?>
 <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-button button alt wc-forward">
 <?php esc_html_e( 'Ga door naar afrekenen →', 'woocommerce' ); ?>
 </a>
 <?php
}
?>


 
 <?php 
  /**
  function checkout1(){
 add_action( 'woocommerce_before_cart', 'move_proceed_button' );
function move_proceed_button( $checkout ) {
    echo '<div class="mobile-checkout-btn text-right"><a href="' . esc_url( WC()->cart->get_checkout_url() ) . '" class="checkout-button button alt wc-forward" >' . __( ' Ga door naar afrekenen → ', 'woocommerce' ) . '</a></div>';
}
 
}

add_shortcode('checkout12', 'checkout1');
 **/
  ?>
  <?php
  
  /** add continue_shopping_button in cart page 
  
add_action( 'woocommerce_after_cart_totals', 'tl_continue_shopping_button' );
function tl_continue_shopping_button() {
 $shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
 
 echo '<div class="shop1">';
 echo ' <a href="'.$shop_page_url.'" class="button"> Doorgaan met winkelen →</a>';
 echo '</div>';
}
 
 **/
 ?>

<?php
/**
 * Rename product data tabs
 */
add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {
	$tabs['description']['title'] = __( 'Omschrijving' );		// Rename the description tab
	$tabs['reviews']['title'] = __( 'beoordelingen' );	
	$tabs['additional_information']['title'] = __( 'Extra informatie' );
	return $tabs;
}

/**
 * Remove product data tabs
 */
 
 add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {
    unset( $tabs['reviews'] );          // Remove the reviews tab
    unset( $tabs['additional_information'] );   // Remove the additional information tab
    return $tabs;
}


?>
<?php
//Change the 'Billing details' checkout label to 'Contact Information'
function wc_billing_field_strings( $translated_text, $text, $domain ) {
switch ( $translated_text ) {
case 'Billing details' :
$translated_text = __( 'Factuurgegevens', 'woocommerce' );
break;
}
return $translated_text;
}
add_filter( 'gettext', 'wc_billing_field_strings', 20, 3 );
?>
<?php
/*
Change Place Order button text on checkout page in woocommerce
*/
add_filter('woocommerce_order_button_text','custom_order_button_text',1);

function custom_order_button_text($order_button_text) {
	
	$order_button_text = 'Plaats bestelling';
	
	return $order_button_text;
}
?>
<?php
// To change add to cart text on single product page
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text' );

function woocommerce_custom_single_add_to_cart_text() {
    return __( 'Voeg toe aan winkelkar', 'woocommerce' );
}
?>
<?php
// Alter WooCommerce View Cart Text
add_filter( 'gettext', function( $translated_text ) {
    if ( 'View cart' === $translated_text ) {
        $translated_text = 'Controleer winkelwagen';
    }
    return $translated_text;
} );
?>
<?php
// Alter WooCommerce View Cart Text
add_filter( 'gettext', function( $translated_text ) {
    if ( 'Update cart' === $translated_text ) {
        $translated_text = 'Winkelwagen bijwerken';
    }
    return $translated_text;
} );
?>
<?php
add_action( 'woocommerce_after_add_to_cart_button', 'add_content_after_addtocart_button_func' );
function add_content_after_addtocart_button_func() {

// Echo content.
 return dynamic_sidebar( 'astra-woo-shop-sidebar' );
} ?>
<?php
// remove sku code 
add_filter( 'wc_product_sku_enabled', '__return_false' );?>
<?php



/**
 * Add a custom product data tab

add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );
function woo_new_product_tab( $tabs ) {	
	// Adds the new tab
	$tabs['test_tab'] = array(
		'title' 	=> __( 'Specificaties', 'woocommerce' ),
		'priority' 	=> 20,
		'callback' 	=> 'woo_new_product_tab_content'
	);
	return $tabs;
}
 */

?>

<?php
//add_action( 'woocommerce_after_single_product', 'bbloomer_custom_action', 5 );
 function bbloomer_custom_action() {
	 ?>
<?php
echo '<h2>Specificaties</h2>';
	?>
	<table class="data table additional-attributes uni-cls" id="product-attribute-specs-table">
   <tbody>
   <?php
    $weight= get_field('weight');
    $brand= get_field('brand');
    $color= get_field('color');
    $style= get_field('style');
    $height= get_field('height');
    $depth= get_field('depth');
    $width= get_field('width');
    $material_base= get_field('material_base');
    $undercarriage_type= get_field('undercarriage_type');
    $seat_material= get_field('seat_material');
    $seat_height_cm= get_field('seat_height_cm');
    $seat_depth= get_field('seat_depth');
    $carrying_capacity= get_field('carrying_capacity');
    $seat_swivel_function= get_field('seat_swivel_function');
    $armrest_height= get_field('armrest_height');
    $chassis_type= get_field('chassis_type');
	if (!empty($weight)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Gewicht</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $weight; ?></font></font></td>
      </tr>
	<?php } 
	if (!empty($brand)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Merk</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $brand; ?></font></font></td>
      </tr>
	<?php } 
	if (!empty($color)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Kleur</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $color; ?></font></font></td>
      </tr>
	<?php }
	if (!empty($style)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Stijl</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $style; ?></font></font></td>
      </tr>
	<?php } 
	if (!empty($height)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Hoogte</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $height; ?></font></font></td>
      </tr>
	<?php } 
	if (!empty($depth)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Diepte</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $depth; ?></font></font></td>
      </tr>
	<?php }
    	if (!empty($width)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Breedte</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $width; ?></font></font></td>
      </tr>
	<?php }
	if (!empty($material_base)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Materiaal basis
</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $material_base; ?></font></font></td>
      </tr>
	<?php } 
	if (!empty($undercarriage_type)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Type onderwagen
</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $undercarriage_type; ?></font></font></td>
      </tr>
	<?php }
	if (!empty($seat_material)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Seat Materiaal
</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $seat_material; ?></font></font></td>
      </tr>
	<?php } 
	if (!empty($seat_height_cm)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Zithoogte Cm
</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $seat_height_cm; ?></font></font></td>
      </tr>
	<?php }
	if (!empty($seat_depth)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Zitdiepte</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $seat_depth; ?></font></font></td>
      </tr>
	<?php } 
	if (!empty($carrying_capacity)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Draagvermogen</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $carrying_capacity; ?></font></font></td>
      </tr>
	<?php } 
		if (!empty($seat_swivel_function)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Draaifunctie zitting</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $seat_swivel_function; ?></font></font></td>
      </tr>
	<?php }
	
	if (!empty($armrest_height)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Hoogte armleuning</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $armrest_height; ?></font></font></td>
      </tr>
	<?php } 
	
    if (!empty($chassis_type)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Type onderstel</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $chassis_type; ?></font></font></td>
      </tr>
	<?php } ?>
	
    
   </tbody>
</table>
<?php } ?>



<?php /* Create Buy Now Button dynamically after Add To Cart button */
    function add_content_after_addtocart() {
    
        // get the current post/product ID
        $current_product_id = get_the_ID();
    
        // get the product based on the ID
        $product = wc_get_product( $current_product_id );
    
        // get the "Checkout Page" URL
        $checkout_url = WC()->cart->get_checkout_url();
    
        // run only on simple products
        if( $product->is_type( 'simple' ) ){
            echo '<a href="'.$checkout_url.'?add-to-cart='.$current_product_id.'" class="buy-now button">Koop nu</a>';
            //echo '<a href="'.$checkout_url.'" class="buy-now button">Buy Now</a>';
        }
    }
    add_action( 'woocommerce_after_add_to_cart_button', 'add_content_after_addtocart' );?>
	
	
	
	
	
	
	<?php 
function tutsplus_widgets_init() {
 
    // First footer widget area, located in the footer. Empty by default.
    register_sidebar( array(
        'name' => __( 'Shop Widget', 'tutsplus' ),
        'id' => 'first-footer-widget-area',
        'description' => __( 'The first footer widget area', 'tutsplus' ),
        'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
}
 
// Register sidebars by running tutsplus_widgets_init() on the widgets_init hook.
add_action( 'widgets_init', 'tutsplus_widgets_init' );

?>
<?php
function wooc_extra_register_fields() {?>
       <p class="form-row form-row-first">
       <label for="reg_billing_first_name"><?php _e( 'Voornaam', 'woocommerce' ); ?><span class="required">*</span></label>
       <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
       </p>
       <p class="form-row form-row-last">
       <label for="reg_billing_last_name"><?php _e( 'Achternaam', 'woocommerce' ); ?><span class="required">*</span></label>
       <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
       </p>
	          <p class="form-row form-row-wide">
       <label for="reg_billing_phone"><?php _e( 'Telefoon', 'woocommerce' ); ?></label>
       <input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php esc_attr_e( $_POST['billing_phone'] ); ?>" />
       </p>
       <div class="clear"></div>
       <?php
 }
 add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );
?>
<?php add_filter( 'gettext', 'wppb_change_text_login', 10, 3 );
function wppb_change_text_login( $translated_text, $text, $domain ) {
    // Only on my account registering form
    if ( ! is_user_logged_in() && is_account_page() ) {
        $original_text = 'Username or email address';

        if ( $text === $original_text )
            $translated_text = esc_html__('Gebruikersnaam of e-mailadres', $domain );
    }
	    if ( ! is_user_logged_in() && is_account_page() ) {
        $original_text = 'Username';

        if ( $text === $original_text )
            $translated_text = esc_html__('Gebruikersnaam', $domain );
    }
	
		    if ( ! is_user_logged_in() && is_account_page() ) {
        $original_text = 'Email address';

        if ( $text === $original_text )
            $translated_text = esc_html__('e-mailadres', $domain );
    }
	
			    if ( ! is_user_logged_in() && is_account_page() ) {
        $original_text = 'Register';

        if ( $text === $original_text )
            $translated_text = esc_html__('Registreren', $domain );
    }
	
	    if ( ! is_user_logged_in() && is_account_page() ) {
        $original_text = 'Password';

        if ( $text === $original_text )
            $translated_text = esc_html__('Wachtwoord', $domain );
    }
		    if ( ! is_user_logged_in() && is_account_page() ) {
        $original_text = 'Remember me';

        if ( $text === $original_text )
            $translated_text = esc_html__('Onthoud me', $domain );
    }
	
			    if ( ! is_user_logged_in() && is_account_page() ) {
        $original_text = 'Lost your password?';

        if ( $text === $original_text )
            $translated_text = esc_html__('Wachtwoord vergeten?', $domain );
    }
	

    return $translated_text;
}


add_filter( 'woocommerce_my_account_get_addresses', 'er45d_woo_change_title_account' );
function er45d_woo_change_title_account( $account_title ) {
	$account_title = array(
		'billing' => __( 'Facturering', 'text-domain' ),
		'shipping' => __( 'Verzending', 'text-domain' ),
	);
	
	return $account_title;
}

/**
 * Trim zeros in price decimals
 **/
 add_filter( 'woocommerce_price_trim_zeros', '__return_true' );



/**
* @snippet       Rename Edit Address Tab @ My Account
* @how-to        Get CustomizeWoo.com FREE
* @author        Rodolfo Melogli
* @testedwith    WooCommerce 5.0
* @donate $9     https://businessbloomer.com/bloomer-armada/
*/
 
add_filter( 'woocommerce_account_menu_items', 'bbloomer_rename_address_my_account', 9999 );
 
function bbloomer_rename_address_my_account( $items ) {
	
 $items = array(
   'dashboard'       => __( 'Dashboard', 'woocommerce' ),
   'orders'          => __( 'Orders', 'woocommerce' ),
   'downloads'       => __( 'Downloads', 'woocommerce' ),
   'edit-address'    => _n( 'Addresses', 'Address', (int) wc_shipping_enabled(), 'woocommerce' ),
   'payment-methods' => __( 'Payment methods', 'woocommerce' ),
   'edit-account'    => __( 'Account details', 'woocommerce' ),
   'customer-logout' => __( 'Logout', 'woocommerce' ),
);
   return $items;
}

// Remove all currency symbols
function sww_remove_wc_currency_symbols( $currency_symbol, $currency ) {
     $currency_symbol = '';
     return $currency_symbol;
}
add_filter('woocommerce_currency_symbol', 'sww_remove_wc_currency_symbols', 10, 2);



// display upsells and related products within dedicated div with different column and number of products
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products2',10);
remove_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products2',10);
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products2', 10);

function woocommerce_output_related_products2() {
 ?>
<?php
echo '<h2>Specificaties</h2>';
	?>
	<table class="data table additional-attributes uni-cls" id="product-attribute-specs-table">
   <tbody>
   <?php
    $weight= get_field('weight');
    $brand= get_field('brand');
    $color= get_field('color');
    $style= get_field('style');
    $height= get_field('height');
    $depth= get_field('depth');
    $width= get_field('width');
    $material_base= get_field('material_base');
    $undercarriage_type= get_field('undercarriage_type');
    $seat_material= get_field('seat_material');
    $seat_height_cm= get_field('seat_height_cm');
    $seat_depth= get_field('seat_depth');
    $carrying_capacity= get_field('carrying_capacity');
    $seat_swivel_function= get_field('seat_swivel_function');
    $armrest_height= get_field('armrest_height');
    $chassis_type= get_field('chassis_type');
	if (!empty($weight)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Gewicht</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $weight; ?></font></font></td>
      </tr>
	<?php } 
	if (!empty($brand)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Merk</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $brand; ?></font></font></td>
      </tr>
	<?php } 
	if (!empty($color)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Kleur</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $color; ?></font></font></td>
      </tr>
	<?php }
	if (!empty($style)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Stijl</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $style; ?></font></font></td>
      </tr>
	<?php } 
	if (!empty($height)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Hoogte</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $height; ?></font></font></td>
      </tr>
	<?php } 
	if (!empty($depth)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Diepte</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $depth; ?></font></font></td>
      </tr>
	<?php }
    	if (!empty($width)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Breedte</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $width; ?></font></font></td>
      </tr>
	<?php }
	if (!empty($material_base)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Materiaal basis
</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $material_base; ?></font></font></td>
      </tr>
	<?php } 
	if (!empty($undercarriage_type)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Type onderwagen
</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $undercarriage_type; ?></font></font></td>
      </tr>
	<?php }
	if (!empty($seat_material)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Seat Materiaal
</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $seat_material; ?></font></font></td>
      </tr>
	<?php } 
	if (!empty($seat_height_cm)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Zithoogte Cm
</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $seat_height_cm; ?></font></font></td>
      </tr>
	<?php }
	if (!empty($seat_depth)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Zitdiepte</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $seat_depth; ?></font></font></td>
      </tr>
	<?php } 
	if (!empty($carrying_capacity)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Draagvermogen</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $carrying_capacity; ?></font></font></td>
      </tr>
	<?php } 
		if (!empty($seat_swivel_function)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Draaifunctie zitting</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $seat_swivel_function; ?></font></font></td>
      </tr>
	<?php }
	
	if (!empty($armrest_height)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Hoogte armleuning</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $armrest_height; ?></font></font></td>
      </tr>
	<?php } 
	
    if (!empty($chassis_type)) { ?> 
      <tr>
         <th class="col label" scope="row"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Type onderstel</font></font></th>
	<td class="col data" data-th="Gewicht"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $chassis_type; ?></font></font></td>
      </tr>
	<?php } ?>
	
    
   </tbody>
</table>
<?php }
add_filter( 'woocommerce_get_price_html', 'cw_change_product_price_display' );
add_filter( 'woocommerce_cart_item_price', 'cw_change_product_price_display' );
function cw_change_product_price_display( $price ) {
    // Your additional text in a translatable string
    $text = __(',-');

    // returning the text before the price
    return $price.''.$text;
}



add_action( 'woocommerce_before_single_product_summary' , 'my_custom_contnet',  );
 
function my_custom_contnet() {
   echo '<div class="custom" style="background: #fdfd5a; clear:left; width:50%">';
   echo '<p>My custom contnet here..</p>';
   echo '</div>';
}

add_filter( 'gettext', function( $translated_text ) {
    if ( 'Subtotal' === $translated_text ) {
        $translated_text = 'Subtotaal';
    }
    return $translated_text;
} );

add_filter( 'gettext', function( $translated_text ) {
    if ( 'Total' === $translated_text ) {
        $translated_text = 'Totaal';
    }
    return $translated_text;
} );

add_filter( 'gettext', function( $translated_text ) {
    if ( 'Please enter a coupon code.' === $translated_text ) {
        $translated_text = 'Voer een couponcode in.';
    }
    return $translated_text;
} );

add_filter( 'gettext', function( $translated_text ) {
    if ( 'Additional information' === $translated_text ) {
        $translated_text = 'Extra informatie';
    }
    return $translated_text;
} );

add_filter( 'gettext', function( $translated_text ) {
    if ( 'Your order' === $translated_text ) {
        $translated_text = 'Jouw bestelling';
    }
    return $translated_text;
} );

add_filter( 'gettext', function( $translated_text ) {
    if ( 'First name' === $translated_text ) {
        $translated_text = 'Voornaam';
    }
    return $translated_text;
} );

add_filter( 'gettext', function( $translated_text ) {
    if ( 'Last name' === $translated_text ) {
        $translated_text = 'Achternaam';
    }
    return $translated_text;
} );


add_filter( 'gettext', function( $translated_text ) {
    if ( 'Company name (optional)' === $translated_text ) {
        $translated_text = 'Bedrijfsnaam (optioneel)';
    }
    return $translated_text;
} );


add_filter( 'gettext', function( $translated_text ) {
    if ( 'Country / Region' === $translated_text ) {
        $translated_text = 'Land / Regio';
    }
    return $translated_text;
} );

add_filter( 'gettext', function( $translated_text ) {
    if ( 'Street address' === $translated_text ) {
        $translated_text = 'Adres';
    }
    return $translated_text;
} );
add_filter( 'gettext', function( $translated_text ) {
    if ( 'Postcode / ZIP' === $translated_text ) {
        $translated_text = 'Postcode / ZIP';
    }
    return $translated_text;
} );


add_filter( 'gettext', function( $translated_text ) {
    if ( 'Town / City' === $translated_text ) {
        $translated_text = 'Stad / Stad';
    }
    return $translated_text;
} );

add_filter( 'gettext', function( $translated_text ) {
    if ( 'Phone' === $translated_text ) {
        $translated_text = 'Telefoon';
    }
    return $translated_text;
} );

add_filter( 'gettext', function( $translated_text ) {
    if ( 'Email address' === $translated_text ) {
        $translated_text = 'E-mailadres';
    }
    return $translated_text;
} );

add_filter( 'gettext', function( $translated_text ) {
    if ( 'Order notes' === $translated_text ) {
        $translated_text = 'Bestelnotities';
    }
    return $translated_text;
} );

add_filter( 'gettext', function( $translated_text ) {
    if ( 'optional' === $translated_text ) {
        $translated_text = 'optioneel';
    }
    return $translated_text;
} );

add_filter( 'gettext', function( $translated_text ) {
    if ( 'Have a coupon?' === $translated_text ) {
        $translated_text = 'heb je een waardebon?';
    }
    return $translated_text;
} );



add_filter( 'gettext', function( $translated_text ) {
    if ( 'Click here to enter your code' === $translated_text ) {
        $translated_text = 'Klik hier om uw code in te voeren';
    }
    return $translated_text;
} );

add_filter( 'gettext', function( $translated_text ) {
    if ( 'House number and street name' === $translated_text ) {
        $translated_text = 'Huisnummer en straatnaam';
    }
    return $translated_text;
} );


add_filter( 'gettext', function( $translated_text ) {
    if ( 'Apartment, suite, unit, etc. (optional)' === $translated_text ) {
        $translated_text = 'Appartement, suite, unit, etc. (optioneel)';
    }
    return $translated_text;
} );


add_filter( 'gettext', function( $translated_text ) {
    if ( 'Notes about your order, e.g. special notes for delivery.' === $translated_text ) {
        $translated_text = 'Opmerkingen over uw bestelling, b.v. speciale opmerkingen voor levering.';
    }
    return $translated_text;
} );


