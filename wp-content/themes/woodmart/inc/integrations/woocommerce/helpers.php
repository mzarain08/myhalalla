<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Add theme support for WooCommerce
 * ------------------------------------------------------------------------------------------------
 */

add_theme_support( 'woocommerce' );
add_theme_support( 'wc-product-gallery-zoom' );


/**
 * ------------------------------------------------------------------------------------------------
 * Check if WooCommerce is active
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_woocommerce_installed' ) ) {
	function woodmart_woocommerce_installed() {
	    return class_exists( 'WooCommerce' );
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * is ajax request
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_is_woo_ajax' ) ) {
	function woodmart_is_woo_ajax() {
		
		$request_headers = getallheaders();

		if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return 'wp-ajax';
		}
		if( isset( $request_headers['x-pjax'] ) || isset( $request_headers['X-PJAX'] ) || isset( $request_headers['X-Pjax'] ) ) {
			return 'full-page';
		}
		if( isset( $_REQUEST['woo_ajax'] ) ) {
			return 'fragments';
		}
		if( isset( $_REQUEST['_pjax'] ) ) {
			return 'full-page';
		}

		if( woodmart_is_pjax() ) {
			return true;
		}
		return false;
	}
}

if( ! function_exists( 'woodmart_is_pjax' ) ) {
	function woodmart_is_pjax(){
		return isset( $_REQUEST['_pjax'] );
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Force WOODMART Swatche layered nav and price widget to work
 * ------------------------------------------------------------------------------------------------
 */


add_filter( 'woocommerce_is_layered_nav_active', 'woodmart_is_layered_nav_active' );
if( ! function_exists( 'woodmart_is_layered_nav_active' ) ) {
	function woodmart_is_layered_nav_active() {
		return is_active_widget( false, false, 'woodmart-woocommerce-layered-nav', true );
	}
}

add_filter( 'woocommerce_is_price_filter_active', 'woodmart_is_layered_price_active' );

if( ! function_exists( 'woodmart_is_layered_price_active' ) ) {
	function woodmart_is_layered_price_active() {
		$result = is_active_widget( false, false, 'woodmart-price-filter', true );
		if( ! $result ) {
			$result = apply_filters( 'woodmart_use_custom_price_widget', true );
		}
		return $result;
	}
}

if( ! function_exists( 'woodmart_get_current_term_id' ) ) {
	/**
	 * FIX CMB2 bug
	 */
	function woodmart_get_current_term_id() {
		return isset( $_REQUEST['tag_ID'] ) ? $_REQUEST['tag_ID'] : 0;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Woodmart is product thumb enabled
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_is_product_thumb_enabled' ) ) {
	function woodmart_is_product_thumb_enabled() {
		$thums_position = woodmart_get_opt('thums_position');
		$product_design = woodmart_get_opt('product_design');
		return ( $product_design != 'sticky' && ( $thums_position == 'bottom' || $thums_position == 'left' ) );
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Woodmart is main product images carousel
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_is_main_product_images_carousel' ) ) {
	function woodmart_is_main_product_images_carousel() {
		$thums_position = woodmart_get_opt( 'thums_position' );
		return ( $thums_position == 'without' || $thums_position == 'centered' ) ? true : woodmart_is_product_thumb_enabled();
	}
}
 
/**
 * ------------------------------------------------------------------------------------------------
 * Determine is it product attribute archieve page
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_is_product_attribute_archieve' ) ) {
	function woodmart_is_product_attribute_archieve() {
	    $queried_object = get_queried_object();
	    if( $queried_object && property_exists( $queried_object, 'taxonomy' ) ) {
	        $taxonomy = $queried_object->taxonomy;
	        return substr($taxonomy, 0, 3) == 'pa_';
	    }
	    return false;
	}
} 