<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Compare button
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_configure_compare' ) ) {
	add_action( 'init', 'woodmart_configure_compare' );
	function woodmart_configure_compare() {
		global $yith_woocompare;

		if ( woodmart_get_opt( 'compare' ) ) {
			add_action( 'woocommerce_single_product_summary', 'woodmart_before_compare_button', 33 );
        	add_action( 'woocommerce_single_product_summary', 'woodmart_add_to_compare_btn', 33 );
			add_action( 'woocommerce_single_product_summary', 'woodmart_after_compare_button', 37 );

			if( class_exists( 'YITH_Woocompare' ) ) {
				$compare = $yith_woocompare->obj;
				remove_action( 'woocommerce_after_shop_loop_item', array( $compare, 'add_compare_link' ), 20 );
			}


			return;
		}

		if( ! class_exists( 'YITH_Woocompare' ) ) return;

		$compare = $yith_woocompare->obj;

		if ( get_option('yith_woocompare_compare_button_in_products_list') == 'yes' ) {
			remove_action( 'woocommerce_after_shop_loop_item', array( $compare, 'add_compare_link' ), 20 );
		}

        if ( get_option('yith_woocompare_compare_button_in_product_page') == 'yes' ) {
        	add_action( 'woocommerce_single_product_summary', 'woodmart_before_compare_button', 33 );
        	add_action( 'woocommerce_single_product_summary', 'woodmart_after_compare_button', 37 );
        }

	}
}

if( ! function_exists( 'woodmart_compare_add_product_url' ) ) {
    function woodmart_compare_add_product_url( $product_id ) {
    	$action_add = 'yith-woocompare-add-product';
        $url_args = array(
            'action' => 'asd',
            'id' => $product_id
        );
        return apply_filters( 'yith_woocompare_add_product_url', esc_url_raw( add_query_arg( $url_args ) ), $action_add );
    }
}


if( ! function_exists( 'woodmart_compare_styles' ) ) {
	add_action( 'wp_print_styles', 'woodmart_compare_styles', 200 );
	function woodmart_compare_styles() {
		if( ! class_exists( 'YITH_Woocompare' ) ) return;
		$view_action = 'yith-woocompare-view-table';
		if ( ( ! defined('DOING_AJAX') || ! DOING_AJAX ) && ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $view_action ) ) return;
		wp_enqueue_style( 'woodmart-style' );
		wp_enqueue_style( 'woodmart-dynamic-style' );
	}
}

if( ! function_exists( 'woodmart_before_compare_button' ) ) {
	function woodmart_before_compare_button() {
		echo '<div class="compare-btn-wrapper">';
	}
}

if( ! function_exists( 'woodmart_after_compare_button' ) ) {
	function woodmart_after_compare_button() {
		echo '</div>';
	}
}

if( ! function_exists( 'woodmart_compare_btn' ) ) {
	function woodmart_compare_btn() {

		if ( woodmart_get_opt( 'compare' ) ) {
			if ( ! woodmart_get_opt( 'compare_on_grid' ) ) return;
			woodmart_add_to_compare_btn();
			return;
		}

		if( ! class_exists( 'YITH_Woocompare' ) ) return;

		if( get_option('yith_woocompare_compare_button_in_products_list') != 'yes' ) return;

		echo '<div class="product-compare-button">';
            global $product;
            $product_id = $product->get_id();

            // return if product doesn't exist
            if ( empty( $product_id ) || apply_filters( 'yith_woocompare_remove_compare_link_by_cat', false, $product_id ) ) {
				echo '</div>';
	            return;
			}

            $is_button = ! isset( $button_or_link ) || ! $button_or_link ? get_option( 'yith_woocompare_is_button' ) : $button_or_link;

            if ( ! isset( $button_text ) || $button_text == 'default' ) {
                $button_text = get_option( 'yith_woocompare_button_text', esc_html__( 'Compare', 'woodmart' ) );
            }

            printf( '<a href="%s" class="%s" data-product_id="%d" rel="nofollow">%s</a>', woodmart_compare_add_product_url( $product_id ), 'compare' . ( $is_button == 'button' ? ' button' : '' ), $product_id, $button_text );
        
		echo '</div>';
	}
}
