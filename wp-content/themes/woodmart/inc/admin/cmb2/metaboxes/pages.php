<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Register all custom metaboxes with CMB2 API
 */
if ( ! function_exists( 'woodmart_pages_metaboxes' ) ) {
	function woodmart_pages_metaboxes() {
		global $woodmart_transfer_options, $woodmart_prefix;

		// Start with an underscore to hide fields from custom fields list
		$woodmart_prefix = '_woodmart_';

		$woodmart_metaboxes = new_cmb2_box( array(
			// 'cmb_styles' => false, // false to disable the CMB stylesheet
			// 'closed'     => true, // true to keep the metabox closed by default
			'id' => 'page_metabox',
			'title' => esc_html__( 'Page Setting (custom metabox from theme)', 'woodmart' ),
			'object_types' => array('page', 'post', 'portfolio'), // post type
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true, // Show field names on the left
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Header options', 'woodmart' ),
			'id'      => $woodmart_prefix . 'header_options_title',
			'type'    => 'title',
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => 'Custom header for this page',
			'id'      => $woodmart_prefix . 'whb_header',
			'desc'    => 'If you are using our header builder for your header configuration you can select different layout from the list for this particular page.',
			'type'    => 'select',
			'options' => woodmart_get_whb_headers_array()
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Open categories menu', 'woodmart' ),
			'desc'    => esc_html__( 'Always shows categories navigation on this page', 'woodmart' ),
			'id'      => $woodmart_prefix . 'open_categories',
			'type'    => 'checkbox',
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Page title options', 'woodmart' ),
			'id'      => $woodmart_prefix . 'page_title_title',
			'type'    => 'title',
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Disable Page title', 'woodmart' ),
			'desc'    => esc_html__( 'You can hide page heading for this page', 'woodmart' ),
			'id'      => $woodmart_prefix . 'title_off',
			'type'    => 'checkbox',
		) );

		$woodmart_metaboxes->add_field( array(
			'name' => esc_html__( 'Image for page title', 'woodmart' ),
			'desc' => esc_html__( 'Upload an image', 'woodmart' ),
			'id' => $woodmart_prefix . 'title_image',
			'type' => 'file',
			'allow' => array( 'url', 'attachment' ) // limit to just attachments with array( 'attachment' )
		) );

		$woodmart_metaboxes->add_field( array(
			'name' => esc_html__( 'Page title background color', 'woodmart' ),
			'desc' => esc_html__( 'Сhoose a color', 'woodmart' ),
			'id' => $woodmart_prefix . 'title_bg_color',
			'type' => 'colorpicker',
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Text color for title', 'woodmart' ),
			'id'      => $woodmart_prefix . 'title_color',
			'type'    => 'woodmart_button_set',
			'buttons_opts' => array(
				'default' => esc_html__( 'Inherit', 'woodmart' ),
				'light' => esc_html__( 'Light', 'woodmart' ), 
				'dark' => esc_html__( 'Dark', 'woodmart' ),
			),
			'default' => 'default'
		) );

		$woodmart_transfer_options[] = 'page-title-size';
		$woodmart_transfer_options[] = 'main_layout';
		$woodmart_transfer_options[] = 'sidebar_width';

		foreach ($woodmart_transfer_options as $field) {
			if( class_exists('Redux') ){
				$cmb_field = woodmart_redux2cmb_field( $field );
				$woodmart_metaboxes->add_field( $cmb_field );
			}
			if ( $field == 'page-title-size' ) {
				$woodmart_metaboxes->add_field( array(
					'name'    => esc_html__( 'Sidebar options', 'woodmart' ),
					'id'      => $woodmart_prefix . 'sidebar_title',
					'type'    => 'title',
				) );
			}
		}

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Custom sidebar for this page', 'woodmart' ),
			'id'      => $woodmart_prefix . 'custom_sidebar',
			'type'    => 'select',
			'options' => woodmart_get_sidebars_array()
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Footer options', 'woodmart' ),
			'id'      => $woodmart_prefix . 'footer_title',
			'type'    => 'title',
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Disable footer', 'woodmart' ),
			'desc'    => esc_html__( 'You can disable footer for this page', 'woodmart' ),
			'id'      => $woodmart_prefix . 'footer_off',
			'type'    => 'checkbox',
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Disable prefooter', 'woodmart' ),
			'desc'    => esc_html__( 'You can disable prefooter for this page', 'woodmart' ),
			'id'      => $woodmart_prefix . 'prefooter_off',
			'type'    => 'checkbox',
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Disable copyrights', 'woodmart' ),
			'desc'    => esc_html__( 'You can disable copyrights for this page', 'woodmart' ),
			'id'      => $woodmart_prefix . 'copyrights_off',
			'type'    => 'checkbox',
		) );
	}

	add_action( 'cmb2_init', 'woodmart_pages_metaboxes', 10000 );
}


if ( ! function_exists( 'woodmart_posts_categories_metaboxes' ) ) {
	function woodmart_posts_categories_metaboxes() {
		if( ! class_exists('Redux') ) return;

		$blog_design_field = woodmart_redux2cmb_field( 'blog_design' );

		$blog_design_field['name'] .= ' for this category';

		$cmb_term = new_cmb2_box( array(
			'id'               => 'cat_options',
			'object_types'     => array( 'term' ),
			'taxonomies'       => array( 'category' ),
			'new_term_section' => true, // Will display in the "Add New Category" section
		) );

		$cmb_term->add_field($blog_design_field);

	}
	
	add_action( 'cmb2_init', 'woodmart_posts_categories_metaboxes', 10000 );
}