<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Add metaboxesto pages and posts
 * uses CMB plugins
 */

/*
	to fix image uploads for taxonomies
	add to file CMB2hookup.php
	line 197
	if ( in_array( $hook, array( 'edit-tags.php', 'post.php', 'post-new.php', 'page-new.php', 'page.php' ), true ) ) {
 */


/**
 * Require CMB plugin files
 *
 */
if ( ! function_exists( 'woodmart_load_cmb_plugin' ) ) {
	function woodmart_load_cmb_plugin() {
		if ( function_exists( 'new_cmb2_box' ) ) {
			require_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/admin/cmb2/taxonomy-metadata/Taxonomy_MetaData_CMB2.php' );
		}
	}

	add_action( 'cmb2_init', 'woodmart_load_cmb_plugin', 199 );
}

if ( ! function_exists( 'woodmart_redux2cmb_field' ) ) {
	/**
	 * Transfer function from redux to CMB2
	 * @param  string $field      field slug in Redux options
	 * @return array  $cmb_field  CMB compatible field config array
	 */
	function woodmart_redux2cmb_field( $field ) {

		if ( ! class_exists( 'Redux' ) ) {
			return;
		}

		$prefix = '_woodmart_';

		$field = Redux::getField( 'woodmart_options', $field );

		$options = array();
		$default = '';
		$opt_key = 'options';

		switch ($field['type']) {
			case 'image_select':
				$type = 'woodmart_button_set';
				$opt_key = 'buttons_opts';
				$default = 'default';
				
				if ( isset( $field['options']['default'] ) ) {
					$default = 'inherit';
				}
				
				$options = ( ! empty( $field['options'] ) ) ? array($default => array('title' => 'Inherit') ) + $field['options'] : array();
				
				if ( count( $options ) > 4 ) {
					$type = 'select';
					$opt_key = 'options';
				}
				foreach ($options as $key => $option) {
					$options[$key] = ( isset( $options[$key]['alt'] ) ) ? $options[$key]['alt'] : $options[$key]['title'];
				}
			break;

			case 'button_set':
				$type = 'woodmart_button_set';
				$opt_key = 'buttons_opts';
				$default = 'default';

				if ( isset( $field['options']['default'] ) ) {
					$default = 'inherit';
				}

				$options[$default] = 'Inherit';

				foreach ($field['options'] as $key => $value) {
					$options[$key] = $value;
				}
			break;

			case 'select':
				$type = 'select';
				$options['inherit'] = 'Inherit';
				foreach ($field['options'] as $key => $value) {
					$options[$key] = $value;
				}
			break;

			case 'switch':
				$type = 'checkbox';
			break;

			case 'background':
				$type = 'colorpicker';
			break;

			default:
				$type = $field['type'];
			break;
		}

		$cmb_field = array(
			'id' => $prefix . $field['id'],
			'type' => $type,
			'name' => $field['title'],
			$opt_key => $options,
		);

		if ( $default ) {
			$cmb_field['default'] = $default;
		}

		return $cmb_field;
	}
}
if ( ! function_exists( 'woodmart_metaboxes' ) ) {
	function woodmart_metaboxes( $metaboxes ) {
		// Declare your sections
		$boxSections = array();
		$boxSections[] = array(
			'title' => 'Performance',
			'id' => 'performance',
			'icon' => 'el-icon-cog',
			'fields' => array (
				array (
					'id'       => 'product-background',
					'type'     => 'background',
					'title'    => esc_html__( 'Product background', 'woodmart' ),
					'subtitle' => esc_html__( 'Set background for your products page. You can also specify different background for particular products while editing it.', 'woodmart' ),
					'output'   => array('.single-product-content')
				),
			),
		);

		// Declare your metaboxes
		$metaboxes = array();
		$metaboxes[] = array(
			'id'            => 'sidebar',
			'title'         => esc_html__( 'Sidebar', 'woodmart' ),
			'post_types'    => array( 'product' ),
			'position'      => 'normal', // normal, advanced, side
			'priority'      => 'high', // high, core, default, low - Priorities of placement
			'sections'      => $boxSections,
		);

		return $metaboxes;
	}
	
	add_action( 'redux/metaboxes/woodmart_options/boxes', 'woodmart_metaboxes' );
}