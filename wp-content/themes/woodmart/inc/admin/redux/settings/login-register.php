<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

Redux::setSection( $opt_name, array(
	'title' => esc_html__('Login/Register', 'woodmart'),
	'id' => 'social-login',
	'icon' => 'el-icon-group',
	'fields' => array (
		array (
			'id'       => 'login_tabs',
			'type'     => 'switch',
			'title'    => esc_html__('Login page tabs', 'woodmart'),
			'subtitle' => esc_html__('Enable tabs for login and register forms', 'woodmart'),
			'default' => 1
		),
		array (
			'id'       => 'reg_text',
			'type'     => 'editor',
			'title'    => esc_html__('Registration text', 'woodmart'),
			'subtitle' => esc_html__('Show some information about registration on your web-site', 'woodmart'),
			'default' => 'Registering for this site allows you to access your order status and history. Just fill in the fields below, and we\'ll get a new account set up for you in no time. We will only ask you for information necessary to make the purchase process faster and easier.'
		),
		array (
			'id'       => 'login_text',
			'type'     => 'editor',
			'title'    => esc_html__('Login text', 'woodmart'),
			'subtitle' => esc_html__('Show some information about login on your web-site', 'woodmart'),
			'default' => ''
		),
		array (
			'id'       => 'my_account_links',
			'type'     => 'switch',
			'title'    => esc_html__( 'Dashboard icons menu', 'woodmart' ),
			'subtitle' => esc_html__( 'Adds icons blocks to the my account page as a navigation.', 'woodmart' ),
			'default'  => 1
		),
		array (
			'id'       => 'my_account_wishlist',
			'type'     => 'switch',
			'title'    => esc_html__( 'Wishlist on my account page', 'woodmart' ),
			'subtitle' => esc_html__( 'Add wishlist item to default WooCommerce account navigation.', 'woodmart' ),
			'default'  => 1
		),
		array (
			'id'   => 'facebook_info',
			'type' => 'info',
			'style' => 'info',
			'desc' => 'Enable login/register with Facebook on your web-site.
			To do that you need to create an APP on the Facebook <a href="https://developers.facebook.com/" target="_blank">https://developers.facebook.com/</a>.
			Then go to APP settings and copy App ID and App Secret there. You also need to insert Redirect URI like this example "http://YOURSITE.COM/my-account/facebook/int_callback". More information you can get in our <a href="https://xtemos.com/docs/woodmart/faq-guides/configure-facebook-login/" target="_blank">documentation</a>.'
		),
		array (
			'id'       => 'fb_app_id',
			'type'     => 'text',
			'title'    => esc_html__('Facebook App ID', 'woodmart'),
			'default' => ''
		),
		array (
			'id'       => 'fb_app_secret',
			'type'     => 'text',
			'title'    => esc_html__('Facebook App Secret', 'woodmart'),
			'default' => ''
		),
		array (
			'id'   => 'google_info',
			'type' => 'info',
			'style' => 'info',
			'desc' => 'You can enable login/register with Google on your web-site.
			To do that you need to Create a Google APIs project at <a href="https://code.google.com/apis/console/" target="_blank">https://console.developers.google.com/apis/dashboard/</a>.
			Make sure to go to API Access tab and Create an OAuth 2.0 client ID. Choose Web application for Application type. Make sure that redirect URI is set to actual OAuth 2.0 callback URL, usually http://SITE.COM/my-account/google/oauth2callback .
				More information you can get in our <a href="https://xtemos.com/docs/woodmart/faq-guides/configure-google-login/" target="_blank">documentation</a>.'
		),
		array (
			'id'       => 'goo_app_id',
			'type'     => 'text',
			'title'    => esc_html__('Google App ID', 'woodmart'),
			'default' => ''
		),
		array (
			'id'       => 'goo_app_secret',
			'type'     => 'text',
			'title'    => esc_html__('Google App Secret', 'woodmart'),
			'default' => ''
		),
		array (
			'id'   => 'vk_info',
			'type' => 'info',
			'style' => 'info',
			'desc' => 'To enable login/register with vk.com you need to create an APP here <a href="https://vk.com/dev" target="_blank">https://vk.com/dev</a>.
			Then go to APP settings and copy App ID and App Secret there.
			You also need to insert Redirect URI like this example http://YOURSITE.COM/my-account/vkontakte/int_callback'
		),
		array (
			'id'       => 'vk_app_id',
			'type'     => 'text',
			'title'    => esc_html__('VKontakte App ID', 'woodmart'),
			'default' => ''
		),
		array (
			'id'       => 'vk_app_secret',
			'type'     => 'text',
			'title'    => esc_html__('VKontakte App Secret', 'woodmart'),
			'default' => ''
		),
	),
) );