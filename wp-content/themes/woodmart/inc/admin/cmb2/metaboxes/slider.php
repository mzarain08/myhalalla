<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

if ( ! function_exists( 'woodmart_slider_metaboxes' ) ) {
	function woodmart_slider_metaboxes() {
		$metabox = new_cmb2_box( array(
			// 'cmb_styles' => false, // false to disable the CMB stylesheet
			// 'closed'     => true, // true to keep the metabox closed by default
			'id' => 'slide_metabox',
			'title' => esc_html__( 'Slide Settings', 'woodmart' ),
			'object_types' => array('woodmart_slide'), // post type
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true, // Show field names on the left
		) );

		$metabox->add_field( array(
			'name'    => esc_html__( 'Background color', 'woodmart' ),
			'id'      => 'bg_color',
			'type'    => 'colorpicker',
			'default' => '#fefefe'
		) );

		$metabox->add_field( array(
			'name'    => esc_html__( 'Slide image on tablet', 'woodmart' ),
			'id'      => 'bg_image_tablet',
			'type'    => 'file',
			'options' => array(
				'url' => false,
			),
			'text'    => array(
				'add_upload_file_text' => esc_html__( 'Add image', 'woodmart' )
			),
			'preview_size' => array( 100, 100 ),
		) );

		$metabox->add_field( array(
			'name'    => esc_html__( 'Slide image on mobile', 'woodmart' ),
			'id'      => 'bg_image_mobile',
			'type'    => 'file',
			'options' => array(
				'url' => false,
			),
			'text'    => array(
				'add_upload_file_text' => esc_html__( 'Add image', 'woodmart' )
			),
			'preview_size' => array( 100, 100 ),
		) );

		$metabox->add_field( array(
			'name'    => esc_html__( 'Vertical content align', 'woodmart' ),
			'id'      => 'vertical_align',
			'type'    => 'woodmart_images_select',
			'images_opts' => array(
				'top' => array(
					'label' => esc_html__( 'Top', 'woodmart' ),
					'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/top.jpg',
				),
				'middle' => array(
					'label' => esc_html__( 'Middle', 'woodmart' ),
					'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/middle.jpg',
				),
				'bottom' => array(
					'label' => esc_html__( 'Bottom', 'woodmart' ),
					'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/bottom.jpg',
				),
			),
			'default' => 'middle',
		) );

		$metabox->add_field( array(
			'name'    => esc_html__( 'Horizontal content align', 'woodmart' ),
			'id'      => 'horizontal_align',
			'type'    => 'woodmart_images_select',
			'images_opts' => array(
				'left' => array(
					'label' => esc_html__( 'Left', 'woodmart' ),
					'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/left.jpg',
				),
				'center' => array(
					'label' => esc_html__( 'Center', 'woodmart' ),
					'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/center.jpg',
				),
				'right' => array(
					'label' => esc_html__( 'Right', 'woodmart' ),
					'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/right.jpg',
				),
			),
			'default' => 'left',
		) );

		$metabox->add_field( array(
			'name' => esc_html__( 'Content no space', 'woodmart' ),
			'desc' => esc_html__( 'The content block will not have any paddings', 'woodmart' ),
			'id'   => 'content_without_padding',
			'type' => 'checkbox',
		) );

		$metabox->add_field( array(
			'name' => esc_html__( 'Full width content', 'woodmart' ),
			'desc' => esc_html__( 'Takes the slider\'s width', 'woodmart' ),
			'id'   => 'content_full_width',
			'type' => 'checkbox',
		) );

		$metabox->add_field( array(
			'name'        => esc_html__( 'Content width [on desktop]', 'woodmart' ),
			'desc'        => esc_html__( 'Set your value in pixels.', 'woodmart' ),
			'id'          => 'content_width',
			'type'        => 'woodmart_slider',
			'min'         => '100',
			'max'         => '1200',
			'step'        => '5',
			'default'     => '1200', // start value
			'value_label' => 'Value:',
			'attributes' => array(
				'data-conditional-id'    => 'content_full_width',
				'data-conditional-value' => 'off',
			),
		));

		$metabox->add_field( array(
			'name'        => esc_html__( 'Content width [on tablets]', 'woodmart' ),
			'desc'        => esc_html__( 'Set your value in pixels.', 'woodmart' ),
			'id'          => 'content_width_tablet',
			'type'        => 'woodmart_slider',
			'min'         => '100',
			'max'         => '1200',
			'step'        => '5',
			'default'     => '1200', // start value
			'value_label' => 'Value:',
			'attributes' => array(
				'data-conditional-id'    => 'content_full_width',
				'data-conditional-value' => 'off',
			),
		));

		$metabox->add_field( array(
			'name'        => esc_html__( 'Content width [on mobile]', 'woodmart' ),
			'desc'        => esc_html__( 'Set your value in pixels.', 'woodmart' ),
			'id'          => 'content_width_mobile',
			'type'        => 'woodmart_slider',
			'min'         => '50',
			'max'         => '800',
			'step'        => '5',
			'default'     => '300', // start value
			'value_label' => 'Value:',
			'attributes' => array(
				'data-conditional-id'    => 'content_full_width',
				'data-conditional-value' => 'off',
			),
		));

		$metabox->add_field( array(
			'name'             => esc_html__( 'Animation', 'woodmart' ),
			'desc'             => esc_html__( 'Select a content appearance animation', 'woodmart' ),
			'id'               => 'slide_animation',
			'type'             => 'select',
			'show_option_none' => false,
			'default'          => 'none',
			'options'          => array(
				'none' => __( 'None', 'woodmart' ),
				'slide-from-top' => __( 'Slide from top', 'woodmart' ),
				'slide-from-bottom' => __( 'Slide from bottom', 'woodmart' ),
				'slide-from-right' => __( 'Slide from right', 'woodmart' ),
				'slide-from-left' => __( 'Slide from left', 'woodmart' ),
				'top-flip-x' => __( 'Top flip X', 'woodmart' ),
				'bottom-flip-x' => __( 'Bottom flip X', 'woodmart' ),
				'right-flip-y' => __( 'Right flip Y', 'woodmart' ),
				'left-flip-y' => __( 'Left flip Y', 'woodmart' ),
				'zoom-in' => __( 'Zoom in', 'woodmart' ),
			),
		) );

		$slider_metabox = cmb2_get_metabox( array(
			'id'               => 'slider_settings',
			'object_types'     => array( 'term' ),
			'taxonomies'       => array( 'woodmart_slider' ),
			'new_term_section' => true, 
		), woodmart_get_current_term_id(), 'term' );


		$slider_metabox->add_field( array(
			'name' => esc_html__( 'Stretch slider', 'woodmart' ),
			'desc' => esc_html__( 'Make slider full width', 'woodmart' ),
			'id'   => 'stretch_slider',
			'type' => 'checkbox',
		) );

		$slider_metabox->add_field( array(
			'name'        => esc_html__( 'Height on desktop', 'woodmart' ),
			'desc'        => esc_html__( 'Set your value in pixels.', 'woodmart' ),
			'id'          => 'height',
			'type'        => 'woodmart_slider',
			'min'         => '100',
			'max'         => '1200',
			'step'        => '5',
			'default'     => '500', // start value
			'value_label' => 'Value:',
		));

		$slider_metabox->add_field( array(
			'name'        => esc_html__( 'Height on tablet', 'woodmart' ),
			'desc'        => esc_html__( 'Set your value in pixels.', 'woodmart' ),
			'id'          => 'height_tablet',
			'type'        => 'woodmart_slider',
			'min'         => '100',
			'max'         => '1200',
			'step'        => '5',
			'default'     => '500', // start value
			'value_label' => 'Value:',
		));

		$slider_metabox->add_field( array(
			'name'        => esc_html__( 'Height on mobile', 'woodmart' ),
			'desc'        => esc_html__( 'Set your value in pixels.', 'woodmart' ),
			'id'          => 'height_mobile',
			'type'        => 'woodmart_slider',
			'min'         => '100',
			'max'         => '1200',
			'step'        => '5',
			'default'     => '500', // start value
			'value_label' => 'Value:',
		));

		$slider_metabox->add_field( array(
			'name'        => esc_html__( 'Arrows style', 'woodmart' ),
			// 'desc'        => 'Set your value in pixels.',
			'id'          => 'arrows_style',
			'type'        => 'woodmart_images_select',
			'images_opts' => array(
				'1' => array(
					'label' => 'Style 1',
					'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/arrow-style-1.jpg',
				),
				'2' => array(
					'label' => 'Style 2',
					'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/arrow-style-2.jpg',
				),
				'3' => array(
					'label' => 'Style 3',
					'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/arrow-style-3.jpg',
				),
				'0' => array(
					'label' => 'Disable',
					'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/arrow-style-disable.jpg',
				),
			),
			'default'     => '1',
		));

		$slider_metabox->add_field( array(
			'name'        => esc_html__( 'Pagination style', 'woodmart' ),
			// 'desc'        => 'Set your value in pixels.',
			'id'          => 'pagination_style',
			'type'        => 'woodmart_images_select',
			'images_opts' => array(
				'1' => array(
					'label' => esc_html__( 'Style 1', 'woodmart' ),
					'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/pagination-style-1.jpg',
				),
				'2' => array(
					'label' => esc_html__( 'Style 2', 'woodmart' ),
					'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/pagination-style-2.jpg',
				),
				'0' => array(
					'label' => esc_html__( 'Disable', 'woodmart' ),
					'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/pagination-style-disable.jpg',
				),
			),
			'default'     => '1',
		));

		$slider_metabox->add_field( array(
			'name'        => esc_html__( 'Navigation color scheme', 'woodmart' ),
			// 'desc'        => 'Set your value in pixels.',
			'id'          => 'pagination_color',
			'type'        => 'woodmart_images_select',
			'images_opts' => array(
				'light' => array(
					'label' => 'Light',
					'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/pagination-color-light.jpg',
				),
				'dark' => array(
					'label' => 'Dark',
					'image' => WOODMART_ASSETS_IMAGES . '/settings/slider-navigation/pagination-color-dark.jpg',
				),
			),
			'default'     => 'light',
		));

		$slider_metabox->add_field( array(
			'name' => esc_html__( 'Enable autoplay', 'woodmart' ),
			'desc' => 'Rotate slider images automatically.',
			'id'   => 'autoplay',
			'type' => 'checkbox',
		) );
		
		$slider_metabox->add_field( array(
			'name' => esc_html__( 'Init carousel on scroll', 'woodmart' ),
			'desc' => esc_html__( 'This option allows you to init carousel script only when visitor scroll the page to the slider. Useful for performance optimization.', 'woodmart' ),
			'id'   => 'scroll_carousel_init',
			'type' => 'checkbox',
		) );

		$slider_metabox->add_field( array(
			'name'    => esc_html__( 'Slide change animation', 'woodmart' ),
			'id'      => 'animation',
			'type'    => 'woodmart_button_set',
			'buttons_opts' => array(
				'slide' => __( 'Slide', 'woodmart' ),
				'fade'  => __( 'Fade', 'woodmart' ),
			),
			'default' => 'slide',
		) );
	}

	add_action( 'cmb2_init', 'woodmart_slider_metaboxes', 10000 );
}