<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Add to Quote Plugin (YITH)
 * ------------------------------------------------------------------------------------------------
 */
if ( function_exists( 'YITH_YWRAQ_Frontend' ) ) {
	remove_action( 'woocommerce_before_single_product', array( YITH_YWRAQ_Frontend(), 'show_button_single_page' ) );

	if( ! function_exists( 'woodmart_show_YWRAQ_button_single_page' ) ) {
		function woodmart_show_YWRAQ_button_single_page(){
			global $product;

		    if( ! $product ){
			    global  $post;
			    if (  ! $post || ! is_object( $post ) || ! is_singular() ) {
				    return;
			    }
			    $product = wc_get_product( $post->ID );
		    }

		    if( get_option('ywraq_show_button_near_add_to_cart','no') == 'yes' && $product->is_in_stock() && $product->get_price() !== '' ){
			    if( $product->is_type( 'variable' ) ){
				    add_action( 'woocommerce_after_single_variation', array(  YITH_YWRAQ_Frontend(), 'add_button_single_page' ),30 );
			    }else{
				    add_action( 'woocommerce_after_add_to_cart_button', array(  YITH_YWRAQ_Frontend(), 'add_button_single_page' ),15 );
			    }
		    }else{
			    add_action( 'woocommerce_single_product_summary', array( YITH_YWRAQ_Frontend(), 'add_button_single_page' ), 30 );
		    }

		}

		add_action( 'woocommerce_before_single_product', 'woodmart_show_YWRAQ_button_single_page', 35 );
	}
}
