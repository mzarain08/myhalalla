<?php // NOT NEEDED HERE > if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * CSS parts variables for CSS Generator
 * ------------------------------------------------------------------------------------------------
 */

return apply_filters( 'woodmart_css_parts', array(
	'wooComm' => array(
		'id' => 'wooComm',
		'title' => 'WooCommerce',
		'section' => 'WooCommerce styles',
		'checked' => true
	),
	'cat-general' => array(
		'id' => 'cat-general',
		'title' => 'Product categories',
		'section' => 'WooCommerce styles',
		'parent' => 'wooComm',
		'checked' => true
	),
	'cat-default' => array(
		'id' => 'cat-default',
		'title' => 'Default style',
		'section' => 'WooCommerce styles',
		'parent' => 'cat-general',
		'checked' => true
	),
	'cat-alt' => array(
		'id' => 'cat-alt',
		'title' => 'Alternative style',
		'section' => 'WooCommerce styles',
		'parent' => 'cat-general',
		'checked' => true
	),
	'cat-center' => array(
		'id' => 'cat-center',
		'title' => 'Centered style',
		'section' => 'WooCommerce styles',
		'parent' => 'cat-general',
		'checked' => true
	),
	'cat-title-rep' => array(
		'id' => 'cat-title-rep',
		'title' => 'Replace title',
		'section' => 'WooCommerce styles',
		'parent' => 'cat-general',
		'checked' => true
	),
	'wc-shop' => array(
		'id' => 'wc-shop',
		'title' => 'Products grid styles',
		'section' => 'WooCommerce styles',
		'parent' => 'wooComm',
		'checked' => true
	),
	'hov-base' => array(
		'id' => 'hov-base',
		'title' => 'Hover base',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-shop',
		'checked' => true
	),
	'hov-alt' => array(
		'id' => 'hov-alt',
		'title' => 'Hover alternative',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-shop',
		'checked' => true
	),
	'hov-button' => array(
		'id' => 'hov-button',
		'title' => 'Hover button',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-shop',
		'checked' => true
	),
	'hov-full-info' => array(
		'id' => 'hov-full-info',
		'title' => 'Hover full information',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-shop',
		'checked' => true
	),
	'hov-info-alt' => array(
		'id' => 'hov-info-alt',
		'title' => 'Full information alternative',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-shop',
		'checked' => true
	),
	'hov-quick' => array(
		'id' => 'hov-quick',
		'title' => 'Hover quick shop',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-shop',
		'checked' => true
	),
	'hov-standard' => array(
		'id' => 'hov-standard',
		'title' => 'Standard button',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-shop',
		'checked' => true
	),
	'hov-tiled' => array(
		'id' => 'hov-tiled',
		'title' => 'Tiled products',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-shop',
		'checked' => true
	),
	'hov-info-alt' => array(
		'id' => 'hov-info-alt',
		'title' => 'Full information alternative',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-shop',
		'checked' => true
	),
	'hov-list' => array(
		'id' => 'hov-list',
		'title' => 'Products list',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-shop',
		'checked' => true
	),
	'wc-modules' => array(
		'id' => 'wc-modules',
		'title' => 'Modules',
		'section' => 'WooCommerce styles',
		'parent' => 'wooComm',
		'checked' => true
	),
	'quick-view' => array(
		'id' => 'quick-view',
		'title' => 'Quick view',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-modules',
		'checked' => true
	),
	'size-guid' => array(
		'id' => 'size-guid',
		'title' => 'Size guide',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-modules',
		'checked' => true
	),
	'sticky-add-to-cart' => array(
		'id' => 'sticky-add-to-cart',
		'title' => 'Sticky add to cart',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-modules',
		'checked' => true
	),
	'highlight-prod' => array(
		'id' => 'highlight-prod',
		'title' => 'Highlighted product',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-modules',
		'checked' => true
	),
	'bordered-prod' => array(
		'id' => 'bordered-prod',
		'title' => 'Bordered product',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-modules',
		'checked' => true
	),
	'filter-area' => array(
		'id' => 'filter-area',
		'title' => 'Filters area',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-modules',
		'checked' => true
	),
	'compare-prod' => array(
		'id' => 'compare-prod',
		'title' => 'Compare',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-modules',
		'checked' => true
	),
	'layered-nav-widget' => array(
		'id' => 'layered-nav-widget',
		'title' => 'Layered Nav Widget',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-modules',
		'checked' => true
	),
	'el-brand' => array(
		'id' => 'el-brand',
		'title' => 'Brands',
		'section' => 'WooCommerce styles',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'el-product-tabs' => array(
		'id' => 'el-product-tabs',
		'title' => 'Product tabs',
		'section' => 'WooCommerce styles',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'wc-plugins' => array(
		'id' => 'wc-plugins',
		'title' => 'Plugins',
		'section' => 'WooCommerce styles',
		'parent' => 'wooComm',
		'checked' => true
	),
	'yith-compare-prod' => array(
		'id' => 'yith-compare-prod',
		'title' => 'YITH Compare',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-plugins',
		'checked' => true
	),
	'wishlist' => array(
		'id' => 'wishlist',
		'title' => 'Wishlist',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-plugins',
		'checked' => true
	),
	'curr-switch' => array(
		'id' => 'curr-switch',
		'title' => 'Currency switcher',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-plugins',
		'checked' => true
	),
	'germanized' => array(
		'id' => 'germanized',
		'title' => 'Germanized',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-plugins',
		'checked' => true
	),
	'yith-vend' => array(
		'id' => 'yith-vend',
		'title' => 'YITH Vendors',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-plugins',
		'checked' => true
	),
	'vc-vend' => array(
		'id' => 'vc-vend',
		'title' => 'WC Vendors',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-plugins',
		'checked' => true
	),
	'dokon-vend' => array(
		'id' => 'dokon-vend',
		'title' => 'Dokan',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-plugins',
		'checked' => true
	),
	'marketplace-vend' => array(
		'id' => 'marketplace-vend',
		'title' => 'WC Marketplace',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-plugins',
		'checked' => true
	),
	'paypal-express' => array(
		'id' => 'paypal-express',
		'title' => 'PayPal express',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-plugins',
		'checked' => true
	),
	'request-quote' => array(
		'id' => 'request-quote',
		'title' => 'Request quote',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-plugins',
		'checked' => true
	),
	'stripe' => array(
		'id' => 'stripe',
		'title' => 'Stripe',
		'section' => 'WooCommerce styles',
		'parent' => 'wc-plugins',
		'checked' => true
	),
	'rtl' => array(
		'id' => 'rtl',
		'title' => 'RTL language',
		'section' => 'Extra configuration',
		'checked' => false
	),
	'woodmart-dark' => array(
		'id' => 'woodmart-dark',
		'title' => 'Dark color scheme',
		'section' => 'Extra configuration',
		'checked' => false
	),
	'wpbakery' => array(
		'id' => 'wpbakery',
		'title' => 'WPBakery Page Builder',
		'section' => 'WPBakery elements',
		'checked' => true
	),
	'banner' => array(
		'id' => 'banner',
		'title' => 'Promo banner',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'timer' => array(
		'id' => 'timer',
		'title' => 'Countdown timer',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'counter' => array(
		'id' => 'counter',
		'title' => 'Counter',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'map' => array(
		'id' => 'map',
		'title' => 'Google map',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'gallery' => array(
		'id' => 'gallery',
		'title' => 'Images gallery',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'hotspot' => array(
		'id' => 'hotspot',
		'title' => 'Images hotspot',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'info-box' => array(
		'id' => 'info-box',
		'title' => 'Information box',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'instagram' => array(
		'id' => 'instagram',
		'title' => 'Instagram',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'list' => array(
		'id' => 'list',
		'title' => 'Icon list',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'menu-price' => array(
		'id' => 'menu-price',
		'title' => 'Menu price',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'pricing-table' => array(
		'id' => 'pricing-table',
		'title' => 'Pricing table',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'row-divider' => array(
		'id' => 'row-divider',
		'title' => 'Row divider',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'sec-title' => array(
		'id' => 'sec-title',
		'title' => 'Section title',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'responsive-text' => array(
		'id' => 'responsive-text',
		'title' => 'Responsive text block',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'slider' => array(
		'id' => 'slider',
		'title' => 'WoodMart slider',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'team-member' => array(
		'id' => 'team-member',
		'title' => 'Team member',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'testimonial' => array(
		'id' => 'testimonial',
		'title' => 'Testimonials',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'timeline' => array(
		'id' => 'timeline',
		'title' => 'Timeline',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'twitter' => array(
		'id' => 'twitter',
		'title' => 'Twitter',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'deg360' => array(
		'id' => 'deg360',
		'title' => '360 degree view',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'vc-accordion' => array(
		'id' => 'vc-accordion',
		'title' => 'VC Accordion',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'vc-tabs' => array(
		'id' => 'vc-tabs',
		'title' => 'VC Tabs',
		'section' => 'WPBakery elements',
		'parent' => 'wpbakery',
		'checked' => true
	),
	'extra-plugins' => array(
		'id' => 'extra-plugins',
		'title' => 'Plugins',
		'section' => 'Additional plugins',
		'checked' => false
	),
	'bbpress' => array(
		'id' => 'bbpress',
		'title' => 'BBPress',
		'section' => 'Additional plugins',
		'parent' => 'extra-plugins',
		'checked' => false
	),
	'wpcf7' => array(
		'id' => 'wpcf7',
		'title' => 'Contact form 7',
		'section' => 'Additional plugins',
		'parent' => 'extra-plugins',
		'checked' => true
	),
	'mc4wp' => array(
		'id' => 'mc4wp',
		'title' => 'Mailchimp',
		'section' => 'Additional plugins',
		'parent' => 'extra-plugins',
		'checked' => true
	),
	'open-table' => array(
		'id' => 'open-table',
		'title' => 'Open table',
		'section' => 'Additional plugins',
		'parent' => 'extra-plugins',
		'checked' => false
	),
	'wpml' => array(
		'id' => 'wpml',
		'title' => 'WPML',
		'section' => 'Additional plugins',
		'parent' => 'extra-plugins',
		'checked' => true
	),
	'portfolio' => array(
		'id' => 'portfolio',
		'title' => 'Portfolio styles',
		'section' => 'Portfolio',
		'checked' => true
	),
	'project-hover' => array(
		'id' => 'project-hover',
		'title' => 'Portfolio base hover',
		'section' => 'Portfolio',
		'parent' => 'portfolio',
		'checked' => true
	),
	'project-Alt' => array(
		'id' => 'project-Alt',
		'title' => 'Alternate hover',
		'section' => 'Portfolio',
		'parent' => 'portfolio',
		'checked' => true
	),
	'project-under' => array(
		'id' => 'project-under',
		'title' => 'Title under image',
		'section' => 'Portfolio',
		'parent' => 'portfolio',
		'checked' => true
	),
	'project-parallax' => array(
		'id' => 'project-parallax',
		'title' => 'Parallax on mouse move',
		'section' => 'Portfolio',
		'parent' => 'portfolio',
		'checked' => true
	),
	'blog-styles' => array(
		'id' => 'blog-styles',
		'title' => 'Blog style',
		'section' => 'Blog',
		'checked' => true
	),
	'blog-default' => array(
		'id' => 'blog-default',
		'title' => 'Default',
		'section' => 'Blog',
		'parent' => 'blog-styles',
		'checked' => true
	),
	'blog-alt' => array(
		'id' => 'blog-alt',
		'title' => 'Alternative',
		'section' => 'Blog',
		'parent' => 'blog-styles',
		'checked' => true
	),
	'blog-small-img' => array(
		'id' => 'blog-small-img',
		'title' => 'Small images',
		'section' => 'Blog',
		'parent' => 'blog-styles',
		'checked' => true
	),
	'blog-chess' => array(
		'id' => 'blog-chess',
		'title' => 'Chess grid',
		'section' => 'Blog',
		'parent' => 'blog-styles',
		'checked' => true
	),
	'blog-mask' => array(
		'id' => 'blog-mask',
		'title' => 'Image mask',
		'section' => 'Blog',
		'parent' => 'blog-styles',
		'checked' => true
	),
	'extra-features' => array(
		'id' => 'extra-features',
		'title' => 'Features',
		'section' => 'Extra features',
		'checked' => true
	),
	'off-canvas-sidebar' => array(
		'id' => 'off-canvas-sidebar',
		'title' => 'Off-canvas sidebar',
		'section' => 'Extra features',
		'parent' => 'extra-features',
		'checked' => true
	),
	'header-banner' => array(
		'id' => 'header-banner',
		'title' => 'Header banner',
		'section' => 'Extra features',
		'parent' => 'extra-features',
		'checked' => true
	),
	'cookies' => array(
		'id' => 'cookies',
		'title' => 'Cookies notice',
		'section' => 'Extra features',
		'parent' => 'extra-features',
		'checked' => true
	),
	'search' => array(
		'id' => 'search',
		'title' => 'Search form',
		'section' => 'Extra features',
		'parent' => 'extra-features',
		'checked' => true
	),
	'animations' => array(
		'id' => 'animations',
		'title' => 'Elements appearance animations',
		'section' => 'Extra features',
		'parent' => 'extra-features',
		'checked' => true
	),
	'full-menu' => array(
		'id' => 'full-menu',
		'title' => 'Header full screen menu',
		'section' => 'Extra features',
		'parent' => 'extra-features',
		'checked' => true
	),
	'ol-ul-list' => array(
		'id' => 'ol-ul-list',
		'title' => 'Standard ul and ol styles',
		'section' => 'Extra features',
		'parent' => 'extra-features',
		'checked' => true
	),
	'form-rounded' => array(
		'id' => 'form-rounded',
		'title' => 'Rounded forms style',
		'section' => 'Extra features',
		'parent' => 'extra-features',
		'checked' => true
	),
	'form-circle' => array(
		'id' => 'form-circle',
		'title' => 'Circled forms style',
		'section' => 'Extra features',
		'parent' => 'extra-features',
		'checked' => true
	),
	'form-underline' => array(
		'id' => 'form-underline',
		'title' => 'Underlined forms style',
		'section' => 'Extra features',
		'parent' => 'extra-features',
		'checked' => true
	),
	'header-dropdown-dark' => array(
		'id' => 'header-dropdown-dark',
		'title' => 'Header dropdowns dark style',
		'section' => 'Extra features',
		'parent' => 'extra-features',
		'checked' => true
	),

) );