<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

Redux::setSection( $opt_name, array(
	'title' => esc_html__('General', 'woodmart'), 
	'id' => 'general',
	'icon' => 'el-icon-home',
	'fields' => array (
		array (
			'id' => 'favicon',
			'type' => 'media',
			'desc' => 'Upload image: png, ico',
			'operator' => 'and',
			'title' => 'Favicon image',
		),
		array (
			'id' => 'favicon_retina',
			'type' => 'media',
			'desc' => 'Upload image: png, ico',
			'operator' => 'and',
			'title' => 'Favicon retina image',
		),
		array (
			'id'       => 'page_comments',
			'type'     => 'switch',
			'title'    => esc_html__('Show comments on page', 'woodmart'),
			'default' => true
		),
		array (
			'id'       => 'google_map_api_key',
			'type'     => 'text',
			'title'    => esc_html__('Google map API key', 'woodmart'),
			'subtitle' => wp_kses( __('Obtain API key <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">here</a> to use our Google Map VC element.', 'woodmart'), array( 'a' => array( 'href' => true, 'target' => true, ), 'br' => array(), 'strong' => array() ) ),
			'tags'     => 'google api key'
		),
		array (
			'id' => 'custom_404_page',
			'type' => 'select',
			'title' => esc_html__( 'Custom 404 page', 'woodmart' ),
			'subtitle' => esc_html__( 'You can make your custom 404 page', 'woodmart' ),
			'options' => woodmart_get_pages(),
			'default' => 'default',
		),
	),
) );