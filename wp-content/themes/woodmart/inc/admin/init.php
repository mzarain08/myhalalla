<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue admin scripts
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_admin_scripts' ) ) {
	function woodmart_admin_scripts() {
		$version = woodmart_get_theme_info( 'Version' );

		wp_enqueue_script( 'woodmart-admin-scripts', WOODMART_ASSETS . '/js/admin.js', array(), $version, true );

		if( apply_filters( 'woodmart_gradients_enabled', true ) ) {
			wp_enqueue_script( 'woodmart-colorpicker-scripts', WOODMART_ASSETS . '/js/colorpicker.min.js', array(), $version, true );
			wp_enqueue_script( 'woodmart-gradient-scripts', WOODMART_ASSETS . '/js/gradX.min.js', array(), $version, true );
		}
		
		if ( woodmart_get_opt( 'size_guides' ) ) {
			wp_enqueue_script( 'woodmart-edittable-scripts', WOODMART_ASSETS . '/js/jquery.edittable.min.js', array(), $version, true );
		}

		//Slider
		wp_enqueue_script( 'jquery-ui-slider' );

		//Datepicker
		wp_enqueue_script('jquery-ui-datepicker');

		//VC Fields
		wp_enqueue_script( 'woodmart-image-hotspot', WOODMART_ASSETS . '/js/vc-fields/image-hotspot.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-title-divider', WOODMART_ASSETS . '/js/vc-fields/title-divider.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-slider', WOODMART_ASSETS . '/js/vc-fields/slider.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-responsive-size', WOODMART_ASSETS . '/js/vc-fields/responsive-size.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-vc-image-select', WOODMART_ASSETS . '/js/vc-fields/image-select.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-vc-colorpicker', WOODMART_ASSETS . '/js/vc-fields/colorpicker.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-vc-datepicker', WOODMART_ASSETS . '/js/vc-fields/datepicker.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-vc-switch', WOODMART_ASSETS . '/js/vc-fields/switch.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-vc-button-set', WOODMART_ASSETS . '/js/vc-fields/button-set.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-vc-functions', WOODMART_ASSETS . '/js/vc-fields/vc-functions.js', array(), $version, true );

		woodmart_admin_scripts_localize();
	}
	
	add_action('admin_init','woodmart_admin_scripts', 100);
}

if ( ! function_exists( 'woodmart_frontend_editor_enqueue_scripts' ) ) {
	function woodmart_frontend_editor_enqueue_scripts() {
		$version = woodmart_get_theme_info( 'Version' );
		wp_enqueue_script( 'js-cookie', woodmart_get_script_url( 'js.cookie' ), array( 'jquery' ), $version, true );
		woodmart_enqueue_scripts();
		wp_enqueue_script( 'woodmart-frontend-editor-functions', WOODMART_ASSETS . '/js/vc-fields/frontend-editor-functions.js', array(), $version, true );
	}
	
	add_action( 'vc_frontend_editor_enqueue_js_css', 'woodmart_frontend_editor_enqueue_scripts', 100 );
}
 
/**
 * ------------------------------------------------------------------------------------------------
 * Localize admin script function
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_admin_scripts_localize' ) ) {
	function woodmart_admin_scripts_localize() {
		wp_localize_script( 'woodmart-admin-scripts', 'woodmartConfig', woodmart_admin_script_local() );
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Get localization array for admin scripts
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_admin_script_local' ) ) {
	function woodmart_admin_script_local() {
		$localize_data = array(
			'ajax' => admin_url( 'admin-ajax.php' ),
		);

		// If we are on edit product attribute page
		if( ! empty( $_GET['page'] ) && $_GET['page'] == 'product_attributes' && ! empty( $_GET['edit'] ) && function_exists('wc_attribute_taxonomy_name_by_id')) {
			$attribute_id = absint( $_GET['edit'] );
			$attribute_name = wc_attribute_taxonomy_name_by_id( $attribute_id );
			$localize_data['attributeSwatchSize'] = woodmart_wc_get_attribute_term( $attribute_name, 'swatch_size' );

			$localize_data['attributeShowOnProduct'] = woodmart_wc_get_attribute_term( $attribute_name, 'show_on_product' );
		}

		if( class_exists('Redux') ) {
			$redux_options = array();
			$options_key = 'woodmart_options';

			$redux_sections = Redux::getSections($options_key);

			foreach ($redux_sections as $id => $section) {
				if( ! isset( $section['subsection'] ) ) {
					$parent_name = $section['title'];
					$parent_icon = $section['icon'];
				} else {
					$redux_sections[$id]['parent_name'] = $parent_name;
					$redux_sections[$id]['icon'] = $parent_icon;
				}
			}

			$options = Redux::$fields[$options_key];

			foreach ($options as $id => $option) {
				if( ! isset( $option['title'] ) ) continue;
				$text = $option['title'];
				if( isset($option['desc']) ) $text .= ' ' . $option['desc'];
				if( isset($option['subtitle']) ) $text .= ' ' . $option['subtitle'];
				if( isset($option['tags']) ) $text .= ' ' . $option['tags'];

				if( isset( $redux_sections[$option['section_id']]['subsection'] ) ) {
					 $path = $redux_sections[$option['section_id']]['parent_name'] . ' -> ' . $redux_sections[$option['section_id']]['title'];
				} else {
					 $path = $redux_sections[$option['section_id']]['title'];
				}

				$redux_options[] = array(
					'id' => $id,
					'title' => $option['title'],
					'text' => $text,
					'section_id' => $redux_sections[$option['section_id']]['priority'],
					'icon' => $redux_sections[$option['section_id']]['icon'],
					'path' => $path,
				);
			}

			$localize_data['reduxOptions'] = $redux_options;
		}

		$localize_data['searchOptionsPlaceholder'] = esc_js(__('Search for options', 'woodmart'));
		$localize_data['ajaxUrl'] = admin_url( 'admin-ajax.php' );
		$localize_data['demoAjaxUrl'] = WOODMART_DEMO_URL . 'wp-admin/admin-ajax.php';

		return apply_filters( 'woodmart_admin_script_local', $localize_data );
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue admin styles
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_enqueue_admin_styles' ) ) {
	function woodmart_enqueue_admin_styles() {
		$version = woodmart_get_theme_info( 'Version' );
		if ( is_admin() ) {
			wp_enqueue_style( 'woodmart-admin-style', WOODMART_ASSETS . '/css/theme-admin.css', array(), $version );

			if( apply_filters( 'woodmart_gradients_enabled', true ) ) {
				wp_enqueue_style( 'woodmart-colorpicker-style', WOODMART_ASSETS . '/css/colorpicker.css', array(), $version );
				wp_enqueue_style( 'woodmart-gradient-style', WOODMART_ASSETS . '/css/gradX.css', array(), $version );
			}
			if ( woodmart_get_opt( 'size_guides' ) ) {
				wp_enqueue_style( 'woodmart-edittable-style', WOODMART_ASSETS . '/css/jquery.edittable.min.css', array(), $version );
			}

			wp_enqueue_style( 'woodmart-jquery-ui', WOODMART_ASSETS . '/css/jquery-ui.css', array(), $version );
		}

	}

	add_action( 'admin_enqueue_scripts', 'woodmart_enqueue_admin_styles' );
}

