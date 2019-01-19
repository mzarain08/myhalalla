<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * WishList button
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_wishlist_btn' ) ) {
	function woodmart_wishlist_btn() {
		if( class_exists('YITH_WCWL_Shortcode')) echo YITH_WCWL_Shortcode::add_to_wishlist(array());
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * If you are using YITH Products Addons plugin you will need to apply the following workaround to
 * be able to add to cart products with AJAX.
 * File: includes/class.yith-wapo-frontend.php, method: add_to_cart_validation, comment line: return false;
 * File: includes/class.yith-wapo.php, method: init, change line: if ( is_admin() && ! $this->is_quick_view() && ! defined( 'DOING_AJAX' ) && DOING_AJAX ) {
 * ------------------------------------------------------------------------------------------------
 */

/**
 * ------------------------------------------------------------------------------------------------
 * Set wishlist cookie
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_maybe_set_wishlist_cookies' ) ) {
	function woodmart_maybe_set_wishlist_cookies() {
		if( ! class_exists( 'YITH_WCWL' ) ) return;
		if ( ! headers_sent() && did_action( 'wp_loaded' ) ) {
			if ( YITH_WCWL()->count_products() > 0 ) {
				woodmart_set_wishlist_cookies( true );
			} elseif ( isset( $_COOKIE['woodmart_items_in_wishlist'] ) ) {
				woodmart_set_wishlist_cookies( false );
			}
		}
	}
	add_action( 'wp', 'woodmart_maybe_set_wishlist_cookies', 100 ); // Set cookies
	add_action( 'shutdown', 'woodmart_maybe_set_wishlist_cookies', 0 ); // Set cookies before shutdown and ob flushing
}


if( ! function_exists( 'woodmart_set_wishlist_cookies' ) ) {
	function woodmart_set_wishlist_cookies( $set = true ) {
		if( ! class_exists( 'YITH_WCWL' ) || ! function_exists( 'wc_setcookie' ) ) return;
		if ( $set ) {
			wc_setcookie( 'woodmart_items_in_wishlist', 1 );
			wc_setcookie( 'woodmart_wishlist_hash', YITH_WCWL()->count_products() );
		} elseif ( isset( $_COOKIE['woodmart_items_in_wishlist'] ) ) {
			wc_setcookie( 'woodmart_items_in_wishlist', 0, time() - HOUR_IN_SECONDS );
			wc_setcookie( 'woodmart_wishlist_hash', '', time() - HOUR_IN_SECONDS );
		}
		do_action( 'woodmart_set_wishlist_cookies', $set );
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Function returns numbers of items in the wishlist
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_wishlist_number' ) ) {
	function woodmart_wishlist_number() {
		if( ! class_exists( 'YITH_WCWL' ) ) die();
		echo YITH_WCWL()->count_products();
		die();
	}

	add_action( 'wp_ajax_woodmart_wishlist_number', 'woodmart_wishlist_number' );
	add_action( 'wp_ajax_nopriv_woodmart_wishlist_number', 'woodmart_wishlist_number' );

}

