<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Dokan compatibility
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_dokan_edit_product_wrap_start' ) ) {
	function woodmart_dokan_edit_product_wrap_start(){
		echo '<div class="site-content col-12" role="main">';
	}
	add_action( 'dokan_dashboard_wrap_before', 'woodmart_dokan_edit_product_wrap_start', 10 );
}

if( ! function_exists( 'woodmart_dokan_edit_product_wrap_end' ) ) {
	function woodmart_dokan_edit_product_wrap_end(){
		echo '</div>';
	}
	add_action( 'dokan_dashboard_wrap_after', 'woodmart_dokan_edit_product_wrap_end', 10 );
}
