<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

Redux::setSection( $opt_name, array(
	'title' => esc_html__('Other', 'woodmart'),
	'id' => 'other',
	'icon' => 'el-icon-cog',
	'fields' => array (
		array (
			'id'       => 'dummy_import',
			'type'     => 'switch',
			'title'    => esc_html__( 'Enable Dummy Content import function', 'woodmart' ),
			'default' => true
		),
		array(
			'id'       => 'woodmart_slider',
			'type'     => 'switch',
			'title'    => esc_html__('Enable custom slider', 'woodmart'),
			'description' => esc_html__('If you enable this option, a new post type for sliders will be added to your Dashboard menu. You will be able to create sliders with WPBakery Page Builder and place them on any page on your website.', 'woodmart'),
			'default' => true
		),
	),
) );