<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * Register all of the default WordPress widgets on startup.
 *
 * Calls 'woodmart_widgets_init' action after all of the WordPress widgets have been
 * registered.
 *
 * @since 2.2.0
 */

include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/wph-widget-class.php');

include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-widget-price-filter.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-widget-layered-nav.php');
	
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-wp-nav-menu-widget.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-widget-search.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-widget-sorting.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-user-panel-widget.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-author-area-widget.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-banner-widget.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-instagram-widget.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-static-block-widget.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-widget-recent-posts.php');
include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/widgets/class-widget-twitter.php');

if( ! function_exists( 'woodmart_widgets_init' ) ) {
	function woodmart_widgets_init() {
		if ( !is_blog_installed() )
			return;

		register_widget( 'WOODMART_WP_Nav_Menu_Widget' );
		register_widget( 'WOODMART_Banner_Widget' );
		register_widget( 'WOODMART_Author_Area_Widget' );
		register_widget( 'WOODMART_Instagram_Widget' );
		register_widget( 'WOODMART_Static_Block_Widget' );
		register_widget( 'WOODMART_Recent_Posts' );
		register_widget( 'WOODMART_Twitter' );

		if(	woodmart_woocommerce_installed() ) {
			register_widget( 'WOODMART_User_Panel_Widget' );
			register_widget( 'WOODMART_Widget_Layered_Nav' );
			register_widget( 'WOODMART_Widget_Sorting' );
			register_widget( 'WOODMART_Widget_Price_Filter' );
			register_widget( 'WOODMART_Widget_Search' );
		}

	}

	add_action('widgets_init', 'woodmart_widgets_init');
}



