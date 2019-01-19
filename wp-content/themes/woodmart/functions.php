<?php
/**
 *
 * The framework's functions and definitions
 *
 */

/**
 * ------------------------------------------------------------------------------------------------
 * Define constants.
 * ------------------------------------------------------------------------------------------------
 */
define( 'WOODMART_THEME_DIR', 		get_template_directory_uri() );
define( 'WOODMART_THEMEROOT', 		get_template_directory() );
define( 'WOODMART_IMAGES', 			WOODMART_THEME_DIR . '/images' );
define( 'WOODMART_SCRIPTS', 		WOODMART_THEME_DIR . '/js' );
define( 'WOODMART_STYLES', 			WOODMART_THEME_DIR . '/css' );
define( 'WOODMART_FRAMEWORK', 		'/inc' );
define( 'WOODMART_DUMMY', 			WOODMART_THEME_DIR . '/inc/dummy-content' );
define( 'WOODMART_CLASSES', 		WOODMART_THEMEROOT . '/inc/classes' );
define( 'WOODMART_CONFIGS', 		WOODMART_THEMEROOT . '/inc/configs' );
define( 'WOODMART_HEADER_BUILDER',  WOODMART_THEME_DIR . '/inc/header-builder' );
define( 'WOODMART_ASSETS', 			WOODMART_THEME_DIR . '/inc/admin/assets' );
define( 'WOODMART_ASSETS_IMAGES', 	WOODMART_ASSETS    . '/images' );
define( 'WOODMART_API_URL', 		'https://xtemos.com/licenses/api/' );
define( 'WOODMART_DEMO_URL', 		'https://woodmart.xtemos.com/' );
define( 'WOODMART_PLUGINS_URL', 	WOODMART_DEMO_URL . 'plugins/');
define( 'WOODMART_DUMMY_URL', 		WOODMART_DEMO_URL . 'dummy-content/');
define( 'WOODMART_SLUG', 			'woodmart' );


/**
 * ------------------------------------------------------------------------------------------------
 * Load all CORE Classes and files
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'woodmart_autoload' ) ) {
    function woodmart_autoload($className) {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $className = str_replace('WOODMART_', '', $className);
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        $fileName = WOODMART_CLASSES . DIRECTORY_SEPARATOR . $fileName;
        if( file_exists( $fileName )) {
            require $fileName;
        }
    }

    spl_autoload_register('woodmart_autoload');
}

$woodmart_theme = new WOODMART_Theme();

/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue styles
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_enqueue_styles' ) ) {
	add_action( 'wp_enqueue_scripts', 'woodmart_enqueue_styles', 10000 );

	function woodmart_enqueue_styles() {
		$version = woodmart_get_theme_info( 'Version' );
		$minified = woodmart_get_opt( 'minified_css' ) ? '.min' : '';
		$is_rtl = is_rtl() ? '-rtl' : '';
		$style_url = WOODMART_THEME_DIR . '/style' . $minified . '.css';
		if ( woodmart_woocommerce_installed() && is_rtl() ) {
			$style_url = WOODMART_STYLES . '/style-rtl' . $minified . '.css';
		} elseif ( ! woodmart_woocommerce_installed() ) {
			$style_url = WOODMART_STYLES . '/base' . $is_rtl . $minified . '.css';
		}

		// Custom CSS generated from the dashboard

		$file = get_option('woodmart-generated-css-file');
		if( ! empty( $file ) && ! empty( $file['url'] ) ) {
			$style_url = $file['url'];
		}

		wp_dequeue_style( 'yith-wcwl-font-awesome' );
		wp_dequeue_style( 'vc_pageable_owl-carousel-css' );
		wp_dequeue_style( 'vc_pageable_owl-carousel-css-theme' );
		wp_enqueue_style( 'font-awesome-css', WOODMART_STYLES . '/font-awesome.min.css', array(), $version );
		wp_enqueue_style( 'bootstrap', WOODMART_STYLES . '/bootstrap.min.css', array(), $version );
		wp_enqueue_style( 'woodmart-style', $style_url, array( 'bootstrap' ), $version );

		wp_enqueue_style( 'js_composer_front', false, array(), $version );
		
		// load typekit fonts
		$typekit_id = woodmart_get_opt( 'typekit_id' );

		if ( $typekit_id ) {
			wp_enqueue_style( 'woodmart-typekit', 'https://use.typekit.net/' . esc_attr ( $typekit_id ) . '.css', array(), $version );
		}

		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('wp_print_styles', 'print_emoji_styles');
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue scripts
 * ------------------------------------------------------------------------------------------------
 */
 
if( ! function_exists( 'woodmart_enqueue_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'woodmart_enqueue_scripts', 10000 );

	function woodmart_enqueue_scripts() {
		
		$version = woodmart_get_theme_info( 'Version' );
		/*
		 * Adds JavaScript to pages with the comment form to support
		 * sites with threaded comments (when in use).
		 */
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply', false, array(), $version );
		}
		if( ! woodmart_woocommerce_installed() ) {
			wp_register_script( 'js-cookie', woodmart_get_script_url( 'js.cookie' ), array( 'jquery' ), $version, true );
		}

		wp_dequeue_script( 'flexslider' );
		wp_dequeue_script( 'photoswipe-ui-default' );
		wp_dequeue_script( 'prettyPhoto-init' );
		wp_dequeue_script( 'prettyPhoto' );
		wp_dequeue_style( 'photoswipe-default-skin' );
		if( woodmart_get_opt( 'image_action' ) != 'zoom' ) {
			wp_dequeue_script( 'zoom' );
		}

		wp_enqueue_script( 'waypoints', false, array(), $version );
		wp_enqueue_script( 'wpb_composer_front_js', false, array(), $version );
		wp_enqueue_script( 'imagesloaded', false, array(), $version );
		wp_enqueue_script( 'woodmart-device', woodmart_get_script_url( 'device' ), array( 'jquery' ), $version );

		if( woodmart_get_opt( 'combined_js' ) ) {
		    wp_enqueue_script( 'isotope', woodmart_get_script_url( 'isotope.pkgd' ), array(), $version, true );
		    wp_enqueue_script( 'woodmart-theme', WOODMART_SCRIPTS . '/theme.min.js', array( 'jquery', 'js-cookie' ), $version, true );
		} else {
			wp_enqueue_script( 'woodmart-libraries-base', woodmart_get_script_url( 'libraries-base' ), array(), $version, true );

			if ( woodmart_get_opt( 'disable_nanoscroller' ) != 'disable' ) {
				wp_enqueue_script( 'woodmart-nanoscroller', woodmart_get_script_url( 'jquery.nanoscroller' ), array(), $version, true );
			}

			$minified = woodmart_get_opt( 'minified_js' ) ? '.min' : '';
			$base = ! woodmart_woocommerce_installed() ? '-base' : '';
			wp_enqueue_script( 'woodmart-theme', WOODMART_SCRIPTS . '/functions' . $base . $minified . '.js', array( 'js-cookie' ), $version, true );
			if ( woodmart_get_opt( 'ajax_shop' ) && woodmart_woocommerce_installed() && ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) ) {
				wp_enqueue_script( 'woodmart-pjax', woodmart_get_script_url( 'jquery.pjax' ), array(), $version, true );
			}
		}
 		wp_add_inline_script( 'woodmart-theme', woodmart_settings_js(), 'after' );
		
		wp_register_script( 'woodmart-panr-parallax', woodmart_get_script_url( 'panr-parallax' ), array(), $version, true );
		wp_register_script( 'woodmart-photoswipe', woodmart_get_script_url( 'photoswipe-bundle' ), array(), $version, true );
		wp_register_script( 'woodmart-slick', woodmart_get_script_url( 'slick' ), array(), $version, true );
		wp_register_script( 'woodmart-countdown', woodmart_get_script_url( 'countdown' ), array(), $version, true );
		wp_register_script( 'woodmart-packery-mode', woodmart_get_script_url( 'packery-mode.pkgd' ), array(), $version, true );
		wp_register_script( 'woodmart-vivus', woodmart_get_script_url( 'vivus' ), array(), $version, true );
		wp_register_script( 'woodmart-threesixty', woodmart_get_script_url( 'threesixty' ), array(), $version, true );
		wp_register_script( 'woodmart-justifiedGallery', woodmart_get_script_url( 'jquery.justifiedGallery' ), array(), $version, true );
		wp_register_script( 'woodmart-autocomplete', woodmart_get_script_url( 'jquery.autocomplete' ), array(), $version, true );
		wp_register_script( 'woodmart-sticky-kit', woodmart_get_script_url( 'jquery.sticky-kit' ), array(), $version, true );
		wp_register_script( 'woodmart-parallax', woodmart_get_script_url( 'jquery.parallax' ), array(), $version, true );
		wp_register_script( 'woodmart-parallax-scroll', woodmart_get_script_url( 'parallax-scroll' ), array(), $version, true );
		wp_register_script( 'maplace', woodmart_get_script_url( 'maplace-0.1.3' ), array( 'google.map.api' ), $version, true );
		wp_register_script( 'isotope', woodmart_get_script_url( 'isotope.pkgd' ), array(), $version, true );

		if ( woodmart_woocommerce_installed() ) {
			wp_register_script( 'accounting', WC()->plugin_url() . '/assets/js/accounting/accounting.min.js', array( 'jquery' ), $version, true );
			wp_register_script( 'wc-jquery-ui-touchpunch', WC()->plugin_url() . '/assets/js/jquery-ui-touch-punch/jquery-ui-touch-punch.min.js', array( 'jquery-ui-slider' ), $version, true );
		}
	
		// Add virations form scripts through the site to make it work on quick view
		if( woodmart_get_opt( 'quick_view_variable' ) || woodmart_get_opt( 'quick_shop_variable' ) ) {
			wp_enqueue_script( 'wc-add-to-cart-variation', false, array(), $version );
		}

		$translations = array(
			'adding_to_cart' => esc_html__('Processing', 'woodmart'),
			'added_to_cart' => esc_html__('Product was successfully added to your cart.', 'woodmart'),
			'continue_shopping' => esc_html__('Continue shopping', 'woodmart'),
			'view_cart' => esc_html__('View Cart', 'woodmart'),
			'go_to_checkout' => esc_html__('Checkout', 'woodmart'),
			'loading' => esc_html__('Loading...', 'woodmart'),
			'countdown_days' => esc_html__('days', 'woodmart'),
			'countdown_hours' => esc_html__('hr', 'woodmart'),
			'countdown_mins' => esc_html__('min', 'woodmart'),
			'countdown_sec' => esc_html__('sc', 'woodmart'),
			'wishlist' => ( class_exists( 'YITH_WCWL' ) ) ? 'yes' : 'no',
			'cart_url' => ( woodmart_woocommerce_installed() ) ?  esc_url( wc_get_cart_url() ) : '',
			'ajaxurl' => admin_url('admin-ajax.php'),
			'add_to_cart_action' => ( woodmart_get_opt( 'add_to_cart_action' ) ) ? esc_js( woodmart_get_opt( 'add_to_cart_action' ) ) : 'widget',
			'added_popup' => ( woodmart_get_opt( 'added_to_cart_popup' ) ) ? 'yes' : 'no',
			'categories_toggle' => ( woodmart_get_opt( 'categories_toggle' ) ) ? 'yes' : 'no',
			'enable_popup' => ( woodmart_get_opt( 'promo_popup' ) ) ? 'yes' : 'no',
			'popup_delay' => ( woodmart_get_opt( 'promo_timeout' ) ) ? (int) woodmart_get_opt( 'promo_timeout' ) : 1000,
			'popup_event' => woodmart_get_opt( 'popup_event' ),
			'popup_scroll' => ( woodmart_get_opt( 'popup_scroll' ) ) ? (int) woodmart_get_opt( 'popup_scroll' ) : 1000,
			'popup_pages' => ( woodmart_get_opt( 'popup_pages' ) ) ? (int) woodmart_get_opt( 'popup_pages' ) : 0,
			'promo_popup_hide_mobile' => ( woodmart_get_opt( 'promo_popup_hide_mobile' ) ) ? 'yes' : 'no',
			'product_images_captions' => ( woodmart_get_opt( 'product_images_captions' ) ) ? 'yes' : 'no',
			'ajax_add_to_cart' => ( apply_filters( 'woodmart_ajax_add_to_cart', true ) ) ? woodmart_get_opt( 'single_ajax_add_to_cart' ) : false,
			'all_results' => esc_html__('View all results', 'woodmart'),
			'product_gallery' => woodmart_get_product_gallery_settings(),
			'zoom_enable' => ( woodmart_get_opt( 'image_action' ) == 'zoom') ? 'yes' : 'no',
			'ajax_scroll' => ( woodmart_get_opt( 'ajax_scroll' ) ) ? 'yes' : 'no',
			'ajax_scroll_class' => apply_filters( 'woodmart_ajax_scroll_class' , '.main-page-wrapper' ),
			'ajax_scroll_offset' => apply_filters( 'woodmart_ajax_scroll_offset' , 100 ),
			'infinit_scroll_offset' => apply_filters( 'woodmart_infinit_scroll_offset' , 300 ),
			'product_slider_auto_height' => ( woodmart_get_opt( 'product_slider_auto_height' ) ) ? 'yes' : 'no',
			'price_filter_action' => ( apply_filters( 'price_filter_action' , 'click' ) == 'submit' ) ? 'submit' : 'click',
			'product_slider_autoplay' => apply_filters( 'woodmart_product_slider_autoplay' , false ),
			'loading' => esc_html__( 'Loading...', 'woodmart' ),
			'close' => esc_html__( 'Close (Esc)', 'woodmart' ),
			'share_fb' => esc_html__( 'Share on Facebook', 'woodmart' ),
			'pin_it' => esc_html__( 'Pin it', 'woodmart' ),
			'tweet' => esc_html__( 'Tweet', 'woodmart' ),
			'download_image' => esc_html__( 'Download image', 'woodmart' ),
			'cookies_version' => ( woodmart_get_opt( 'cookies_version' ) ) ? (int)woodmart_get_opt( 'cookies_version' ) : 1,
			'header_banner_version' => ( woodmart_get_opt( 'header_banner_version' ) ) ? (int)woodmart_get_opt( 'header_banner_version' ) : 1,
			'promo_version' => ( woodmart_get_opt( 'promo_version' ) ) ? (int)woodmart_get_opt( 'promo_version' ) : 1,
			'header_banner_close_btn' => woodmart_get_opt( 'header_close_btn' ),
			'header_banner_enabled' => woodmart_get_opt( 'header_banner' ),
			'whb_header_clone' => woodmart_get_config( 'header-clone-structure' ),
			'pjax_timeout' => apply_filters( 'woodmart_pjax_timeout' , 5000 ),
			'split_nav_fix' => apply_filters( 'woodmart_split_nav_fix' , false ),
			'shop_filters_close' => woodmart_get_opt( 'shop_filters_close' ) ? 'yes' : 'no',
			'woo_installed' => woodmart_woocommerce_installed(),
			'base_hover_mobile_click' => woodmart_get_opt( 'base_hover_mobile_click' ) ? 'yes' : 'no',
			'centered_gallery_start' => apply_filters( 'woodmart_centered_gallery_start' , 1 ),
			'woodmart_sticky_desc_scroll' => apply_filters( 'woodmart_sticky_desc_scroll', true ),
			'quickview_in_popup_fix' => apply_filters( 'woodmart_quickview_in_popup_fix', false ),
			'disable_nanoscroller' => woodmart_get_opt( 'disable_nanoscroller' ),
			'one_page_menu_offset' => apply_filters( 'woodmart_one_page_menu_offset', 150 ),
		);
		
		wp_localize_script( 'woodmart-functions', 'woodmart_settings', $translations );
		wp_localize_script( 'woodmart-theme', 'woodmart_settings', $translations );

	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue google fonts
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_enqueue_google_fonts' ) ) {
	add_action( 'wp_enqueue_scripts', 'woodmart_enqueue_google_fonts', 10000 );

	function woodmart_enqueue_google_fonts() {
		$default_google_fonts = 'Lato:100,100i,300,300i,400,400i,700,700i,900,900i|Poppins:300,400,500,600,700';

		if( ! class_exists('Redux') )
   			wp_enqueue_style( 'woodmart-google-fonts', woodmart_get_fonts_url( $default_google_fonts ), array(), woodmart_get_theme_info( 'Version' ) );
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Get google fonts URL
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_get_fonts_url') ) {
	function woodmart_get_fonts_url( $fonts ) {
	    $font_url = '';

        $font_url = add_query_arg( 'family', urlencode( $fonts ), "//fonts.googleapis.com/css" );

	    return $font_url;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Get script URL
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_get_script_url') ) {
	function woodmart_get_script_url( $script_name ) {
	    return WOODMART_SCRIPTS . '/' . $script_name . '.min.js';
	}
}