<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Metaboxes for products
 */
if ( ! function_exists( 'woodmart_product_metaboxes' ) ) {
	function woodmart_product_metaboxes() {
		global $woodmart_prefix, $woodmart_transfer_options;

		// Start with an underscore to hide fields from custom fields list
		$woodmart_prefix = '_woodmart_';
		$taxonomies_list = array( '' => 'Select' );
		$taxonomies = get_taxonomies();
		foreach ( $taxonomies as $taxonomy ) {
			$taxonomies_list[$taxonomy] = $taxonomy;
		}

		$woodmart_metaboxes = new_cmb2_box( array(
			// 'cmb_styles' => false, // false to disable the CMB stylesheet
			// 'closed'     => true, // true to keep the metabox closed by default
			'id' => 'product_metabox',
			'title' => esc_html__( 'Product Setting (custom metabox from theme)', 'woodmart' ),
			'object_types' => array('product'), // post type
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true, // Show field names on the left
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Product design & color options', 'woodmart' ),
			'id'      => $woodmart_prefix . 'product_design_title',
			'type'    => 'title',
		) );

		$woodmart_local_transfer_options = array(
			'product_design',
			'single_product_style',
			'thums_position',
			'product-background',
			'main_layout',
			'sidebar_width',
		);

		foreach ($woodmart_local_transfer_options as $field) {
			if ( $field == 'main_layout' ) {
				$woodmart_metaboxes->add_field( array(
					'name'    => esc_html__( 'Sidebar options', 'woodmart' ),
					'id'      => $woodmart_prefix . 'sidebar_title',
					'type'    => 'title',
				) );
			}
			if( class_exists('Redux') ){
				$cmb_field = woodmart_redux2cmb_field( $field );
				$woodmart_metaboxes->add_field( $cmb_field );
			}
		}

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Custom sidebar for this product', 'woodmart' ),
			'id'      => $woodmart_prefix . 'custom_sidebar',
			'type'    => 'select',
			'options' => woodmart_get_sidebars_array()
		) );

		$blocks = array_flip(woodmart_get_static_blocks_array());

		$blocks = (array)'None' + $blocks;
		
		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Extra content options', 'woodmart' ), 
			'id'      => $woodmart_prefix . 'extra_content_title',
			'type'    => 'title',
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Extra content block', 'woodmart' ),
			'desc'    => esc_html__( 'You can create some extra content with WPBakery Page Builder (in Admin panel / HTML Blocks / Add new) and add it to this product', 'woodmart' ),
			'id'      => $woodmart_prefix . 'extra_content',
			'type'    => 'select',
			'options' => $blocks
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Extra content position', 'woodmart' ),
			'id'      => $woodmart_prefix . 'extra_position',
			'type'    => 'woodmart_button_set',
			'buttons_opts' => array(
				'after' => esc_html__( 'After content', 'woodmart' ),
				'before' => esc_html__( 'Before content', 'woodmart' ),
				'prefooter' => esc_html__( 'Prefooter', 'woodmart' ),
			),
			'default' => 'after'
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Product tab options', 'woodmart' ), 
			'id'      => $woodmart_prefix . 'tabs_title',
			'type'    => 'title',
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Hide tabs headings', 'woodmart' ), 
			'desc'    => esc_html__( 'Description and Additional information', 'woodmart' ),
			'id'      => $woodmart_prefix . 'hide_tabs_titles',
			'type'    => 'checkbox',
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Custom tab title', 'woodmart' ), 
			'id'      => $woodmart_prefix . 'product_custom_tab_title',
			'type'    => 'text',
		) );
		
		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Custom tab content', 'woodmart' ), 
			'id'      => $woodmart_prefix . 'product_custom_tab_content',
			'type'    => 'textarea',
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Product extra options', 'woodmart' ), 
			'id'      => $woodmart_prefix . 'product_extra_title',
			'type'    => 'title',
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Add "New" label', 'woodmart' ), 
			'desc'    => esc_html__( 'You can add "New" label to this product', 'woodmart' ),
			'id'      => $woodmart_prefix . 'new_label',
			'type'    => 'checkbox',
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Hide related products', 'woodmart' ), 
			'desc'    => esc_html__( 'You can hide related products on this page', 'woodmart' ),
			'id'      => $woodmart_prefix . 'related_off',
			'type'    => 'checkbox',
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Grid swatch attribute to display', 'woodmart' ), 
			'desc'    => esc_html__( 'Choose attribute that will be shown on products grid for this particular product', 'woodmart' ),
			'id'      => $woodmart_prefix . 'swatches_attribute',
			'type'    => 'select',
			'options' => $taxonomies_list
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Product video URL', 'woodmart' ), 
			'desc'    => esc_html__( 'Vimeo or YouTube video url. For example: https://www.youtube.com/watch?v=1zPYW6Ipgok', 'woodmart' ),
			'id'      => $woodmart_prefix . 'product_video',
			'type'    => 'text',
		) );

		$woodmart_metaboxes->add_field( array(
			'name'    => esc_html__( 'Instagram product hashtag', 'woodmart' ), 
			'desc'    => wp_kses(  __( 'Insert tag that will be used to display images from instagram from your customers. For example: <strong>#nike_rush_run</strong>', 'woodmart' ), 'default' ),
			'id'      => $woodmart_prefix . 'product_hashtag',
			'type'    => 'text',
		) );

		$woodmart_transfer_options = array_merge( $woodmart_transfer_options, $woodmart_local_transfer_options );
	}

	add_action( 'cmb2_init', 'woodmart_product_metaboxes', 10000 );
}

if ( ! function_exists( 'woodmart_product_categories_metaboxes' ) ) {
	function woodmart_product_categories_metaboxes() {
		/**
		 * Instantiate our taxonomy meta class
		 */

		$cmb_term = cmb2_get_metabox( array(
			'id'               => 'product_cat_options',
			'object_types'     => array( 'term' ),
			'taxonomies'       => array( 'product_cat' ),
			'new_term_section' => true, // Will display in the "Add New Category" section
		), woodmart_get_current_term_id(), 'term' );

		$cmb_term->add_field( array(
			'name' => esc_html__( 'Image for category heading', 'woodmart' ),
			'desc' => esc_html__( 'Upload an image', 'woodmart' ),
			'id' => 'title_image',
			'type' => 'file',
			'allow' => array( 'url', 'attachment' ) // limit to just attachments with array( 'attachment' )
		));

		$cmb_term->add_field(array(
			'name' => esc_html__( 'Image (icon) for categories navigation on the shop page', 'woodmart' ),
			'desc' => esc_html__( 'Upload an image', 'woodmart' ),
			'id' => 'category_icon',
			'type' => 'file',
			'allow' => array( 'url', 'attachment' ) // limit to just attachments with array( 'attachment' )
		));

		$cmb_term->add_field(array(
			'name' => esc_html__( 'Icon to display in the main menu (or any other menu through the site)', 'woodmart' ),
			'desc' => esc_html__( 'Upload an image', 'woodmart' ),
			'id' => 'category_icon_alt',
			'type' => 'file',
			'allow' => array( 'url', 'attachment' ) // limit to just attachments with array( 'attachment' )
		));

	}
	
	add_action( 'cmb2_init', 'woodmart_product_categories_metaboxes', 10000 );
}