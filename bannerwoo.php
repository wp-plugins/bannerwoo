<?php
/**
 * Plugin Name:       BannerWoo
 * Plugin URI:        http://www.bannerwoo.com/
 * Description:       Sell banner ads on autopilot with woocommerce. Simple, clean and lightweight.
 * Version:           1.0.0
 * Author:            Pasquale Bucci
 * Author URI:        http://www.webartsdesign.it/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/*  Copyright 2015  Pasquale Bucci  (email : paky.bucci@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
 
	/**
 	* Load plugin textdomain.
 	*/
	add_action( 'plugins_loaded', 'bannerwoo_load_textdomain' );
	function bannerwoo_load_textdomain() {
  		load_plugin_textdomain( 'bannerwoo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
	}

	/**
 	* Banner size.
 	*/
	add_action( 'init', 'bannerwoo_image_size' );
	function bannerwoo_image_size() {
    	add_image_size( '468x60', 468, 60, true );
    	add_image_size( '728x90', 728, 90, true );
    	add_image_size( '336x280', 336, 280, true );
    	add_image_size( '300x250', 300, 250, true );
    	add_image_size( '250x250', 250, 250, true );
    	add_image_size( '160x600', 160, 600, true );
    	add_image_size( '120x600', 120, 600, true );
    	add_image_size( '120x240', 120, 240, true );
    	add_image_size( '240x400', 240, 400, true );
    	add_image_size( '234x60', 234, 60, true );
    	add_image_size( '180x150', 180, 150, true );
    	add_image_size( '125x125', 125, 125, true );
    	add_image_size( '120x90', 120, 90, true );
    	add_image_size( '120x60', 120, 60, true );
    	add_image_size( '88x31', 88, 31, true );
	}

	/**
 	* Add Banner product selector
 	*/ 
	add_filter( 'product_type_selector', 'bannerwoo_add_product_banner' );
	function bannerwoo_add_product_banner( $types ){
    	$types[ 'banner_type' ] = __( 'Banner product', 'bannerwoo' );
    	return $types;
	}

	/**
 	* Add custom fields in banner product
 	*/ 
	add_action( 'woocommerce_product_options_general_product_data', 'bannerwoo_add_custom_settings' );
	function bannerwoo_add_custom_settings() {
    	global $woocommerce, $post;
		
    	echo '<div class="options_group show_if_banner_type show_bannerwoo">';

    	// Select
		woocommerce_wp_select(
			array(
				'id' => 'bannerwoo_unit_time',
				'label' => __( 'Unit', 'bannerwoo' ),
				'description' => __( 'The quantity unit.', 'bannerwoo' ),
				'options' => array(
					'day' => __( 'Day', 'bannerwoo' ),
					'week' => __( 'Week', 'bannerwoo' ),
					'month' => __( 'Month', 'bannerwoo' ),
					'year' => __( 'Year', 'bannerwoo' )
				)
			)); 

    	// Select
		woocommerce_wp_select(
			array(
				'id' => 'bannerwoo_size_select',
				'label' => __( 'Banner Size', 'bannerwoo' ),
				'options' => array(
					'468,60' => __( '468 x 60 - Full Banner', 'bannerwoo' ),
					'728,90' => __( '728 x 90 - Leaderboard', 'bannerwoo' ),
					'336,280' => __( '336 x 280 - Square', 'bannerwoo' ),
					'300,250' => __( '300 x 250 - Square', 'bannerwoo' ),
					'250,250' => __( '250 x 250 - Square', 'bannerwoo' ),
					'160,600' => __( '160 x 600 - Skyscraper', 'bannerwoo' ),
					'120,600' => __( '120 x 600 - Skyscraper', 'bannerwoo' ),
					'120,240' => __( '120 x 240 - Small Skyscraper', 'bannerwoo' ),
					'240,400' => __( '240 x 400 - Fat Skyscraper', 'bannerwoo' ),
					'234,60' => __( '234 x 60 - Half Banner', 'bannerwoo' ),
					'180,150' => __( '180 x 150 - Rectangle', 'bannerwoo' ),
					'125,125' => __( '125 x 125 - Square Button', 'bannerwoo' ),
					'120,90' => __( '120 x 90 - Button', 'bannerwoo' ),
					'120,60' => __( '120 x 60 - Button', 'bannerwoo' ),
					'88,31' => __( '88 x 31 - Button', 'bannerwoo' )
				)
			)); 

		// hidden bannerwoo_max_rotation
    	woocommerce_wp_hidden_input(
      		array(
       			'id'                => 'bannerwoo_max_rotation',
       			'value' => '1'
       		));

      	// Hidden tipo
		woocommerce_wp_hidden_input(
			array(
				'id' => '_tipo',
				'value' => 'no_banner'
				)
			);

		// Hidden current rotation
		woocommerce_wp_hidden_input(
			array(
				'id' => '_current_rot',
				'value' => '1'
				)
			);

    	echo '</div>';
	}

	/**
 	* Save custom fields in banner product
 	*/ 
	add_action( 'woocommerce_process_product_meta', 'bannerwoo_save_custom_settings' );
	function bannerwoo_save_custom_settings( $post_id ){
		
		// save unit time quantity
		$bannerwoo_unit_time = $_POST['bannerwoo_unit_time'];
		if( !empty( $bannerwoo_unit_time ) )
			update_post_meta( $post_id, 'bannerwoo_unit_time', esc_attr( $bannerwoo_unit_time ) );

		// save select size
		$bannerwoo_size_select = $_POST['bannerwoo_size_select'];
		if( !empty( $bannerwoo_size_select ) )
			update_post_meta( $post_id, 'bannerwoo_size_select', esc_attr( $bannerwoo_size_select ) ); 

		// save max rotation
		$bannerwoo_max_rotation = $_POST['bannerwoo_max_rotation'];
		if( !empty( $bannerwoo_max_rotation ) )
			update_post_meta( $post_id, 'bannerwoo_max_rotation', esc_attr( $bannerwoo_max_rotation) );

		// save hidden tipo
		$bannerwoo_tipo = $_POST['_tipo'];
		if( !empty( $bannerwoo_tipo ) )
		update_post_meta( $post_id, '_tipo', esc_attr( $bannerwoo_tipo ) );

		// save hidden current rotation
		$bannerwoo_current_rotation = $_POST['_current_rot'];
		if( !empty( $bannerwoo_current_rotation ) )
		update_post_meta( $post_id, '_current_rot', esc_attr( $bannerwoo_current_rotation ) );
		
	}

	/**
 	* Display sku & price in banner product
 	*/ 
	add_action('woocommerce_product_options_sku', 'bannerwoo_start_buffer');
	add_action('woocommerce_product_options_pricing', 'bannerwoo_end_buffer');

	function bannerwoo_start_buffer(){
  		ob_start();
	}

	function bannerwoo_end_buffer(){
  		// Get value of buffering so far
  		$getContent = ob_get_contents();

 		// Stop buffering
 		ob_end_clean();

 		$getContent = str_replace('options_group pricing show_if_simple show_if_external', 'options_group pricing show_if_simple show_if_external show_if_banner_type', $getContent);
 		echo $getContent;
	}

	/**
 	* Display price for time unit in single banner product
 	*/
 	add_filter('woocommerce_get_price_html', 'custom_variation_price', 10, 2);
	function custom_variation_price( $price, $product ) {
		$tipo = get_post_meta( $product->id, '_tipo', true );
		if ($tipo == 'bannerwoo') {
    		$price .= ' / ' . get_post_meta( $product->id, 'bannerwoo_unit_time', true );
    	}
    	return $price;
	}

	/**
 	* Add order item meta
 	*/
	add_action( 'woocommerce_add_order_item_meta', 'bannerwoo_order_meta_handler', 1, 3 );
	function bannerwoo_order_meta_handler( $item_id, $values, $cart_item_key ) {
		global $woocommerce;
		wc_add_order_item_meta( $item_id, 'this_banner_rotation', $woocommerce->session->ban_rot );
		unset( $woocommerce->session->ban_rot );	
	}
	
	/**
 	* Exclude banner products on the shop
 	*/
	add_action( 'pre_get_posts', 'bannerwoo_pre_get_posts_query' );
	function bannerwoo_pre_get_posts_query( $q ) {
		if ( ! $q->is_main_query() ) return;
		if ( ! $q->is_post_type_archive() ) return;
		if ( ! is_admin() && is_shop() ) {
			$q->set( 'meta_value', 'no_banner' );
		}
		remove_action( 'pre_get_posts', 'bannerwoo_pre_get_posts_query' );
	}
	
	/**
 	* Fix header already sent
 	*/
	add_action('init', 'add_ob_start');
	add_action('wp_footer', 'flush_ob_end');
	function callback($buffer){
    	return $buffer;
	}
	
	function add_ob_start(){
    	ob_start("callback");
	}

	function flush_ob_end(){
    	ob_end_flush();
	}

	/**
 	* Add query vars banner type
 	*/
	add_filter( 'query_vars', 'add_query_vars_banner' );
	function add_query_vars_banner( $vars ){
  		$vars[] = "banner_type";
  		return $vars;
	}

	/**
 	* Add banner_type endpoint
 	*/
	add_action( 'init', 'bannerwoo_add_banner_endpoint' );
	function bannerwoo_add_banner_endpoint() {
    	add_rewrite_endpoint( 'banner_type', EP_PERMALINK | EP_PAGES );
	}
	
	/**
 	* Redirect to banner_type template
 	*/
 	add_action( 'template_redirect', 'banner_type_template_redirect' );
	function banner_type_template_redirect() {
    	global $wp_query;
 
    	
    	if ( ! isset( $wp_query->query_vars['banner_type'] )  )
        	return;
 
    	// include custom template
    	include dirname( __FILE__ ) . '/includes/banner-type.php';
    	
    	exit;
	}

	/**
 	* Enqueue scripts and styles
 	*/
 	add_action( 'wp_enqueue_scripts', 'bannerwoo_scripts' );
	function bannerwoo_scripts() {
		
		//register scripts
		wp_register_style( 'bannerwoo', plugins_url( 'css/bannerwoo.css', __FILE__ ) );
		
		//enqueue scripts
		wp_enqueue_style( 'bannerwoo' );
		
	}

	/**
 	* Enqueue admin scripts and styles
 	*/
	add_action( 'admin_enqueue_scripts', 'bannerwoo_enqueue_admin_script' );
	function bannerwoo_enqueue_admin_script( $hook_suffix ) {
    	//register scripts
    	wp_register_script( 'bannerwoo-admin', plugins_url('js/admin.js', __FILE__ ), array( 'wp-color-picker' ), '1.0', true );

    	//enqueue scripts
    	wp_enqueue_style( 'wp-color-picker' );
    	wp_enqueue_script( 'bannerwoo-admin' );

	}

	/**
 	* Tinymce
 	*/
 	add_action('admin_head', 'bannerwoo_shortcodes_add_mce_button');
	function bannerwoo_shortcodes_add_mce_button() {
		// check user permissions
		if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
			return;
		}
		// check if WYSIWYG is enabled
		if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', 'bannerwoo_shortcodes_add_tinymce_plugin' );
			add_filter( 'mce_buttons', 'bannerwoo_shortcodes_register_mce_button' );
		}
	}
	
	function bannerwoo_shortcodes_add_tinymce_plugin( $plugin_array ) {
		$plugin_array['bannerwoo_shortcodes_mce_button'] = plugins_url( '/js/bannerwoo_shortcodes_tinymce.js', __FILE__ );
		return $plugin_array;
	}

	function bannerwoo_shortcodes_register_mce_button( $buttons ) {
		array_push( $buttons, 'bannerwoo_shortcodes_mce_button' );
		return $buttons;
	}

	/**
 	* Plugin Action Links
 	*/ 
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'link_action_on_plugin' );
	function link_action_on_plugin( $links ) {
 
		return array_merge( $links, 
			array( 	'settings' => '<a href="' . admin_url( 'admin.php?page=bannerwoo' ) . '">' . __( 'Settings', 'bannerwoo' ) . '</a>',
					'Premium' => '<a href="http://www.bannerwoo.com/">' . __( 'Premium Version', 'bannerwoo' ) . '</a>'
			)
		);
	}

	/**
 	* Require modules
 	*/
 	require_once plugin_dir_path( __FILE__ ) . 'includes/banner-option.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/banner-shortcode.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/banner-widget.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/my-banners.php';

}

?>