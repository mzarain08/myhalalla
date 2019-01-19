<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
 * Main Theme class that initialize all
 * other classes like assets, layouts, options
 *
 * Also includes files with theme functions
 * template tags, 3d party plugins etc.
 */
class WOODMART_Theme {
	/**
	 * Array to register redux fields
	 * @var array
	 */
	private $redux_fields = array();

	/**
	 * Classes array to register shortcodes
	 * @var array
	 */
	private $shortcodes = array();

	/**
	 * Classes array to register shortcodes in js_composer
	 * @var array
	 */
	private $vc_element = array();

	/**
	 * Classes array to register in WOODMART_Registery object
	 * @var array
	 */
	private $register_classes = array();

	/**
	 * Files array to include from inc/ folder
	 * @var array
	 */
	private $files_include = array();

	/**
	 * Array of files to include in admin area
	 * @var array
	 */
	private $admin_files_include = array();

	/**
	 * Call init methods
	 */
	public function __construct() {

		$this->shortcodes = array(
			'3d-view',
			'ajax-search',
			'author-area',
			'blog',
			'button',
			'countdown-timer',
			'counter',
			'extra-menu',
			'gallery',
			'google-map',
			'html-block',
			'images-gallery',
			'info-box',
			'instagram',
			'mega-menu',
			'menu-price',
			'popup',
			'portfolio',
			'posts-slider',
			'pricing-tables',
			'promo-banner',
			'responsive-text-block',
			'row-divider',
			'slider',
			'social',
			'team-member',
			'testimonials',
			'timeline',
			'title',
			'twitter',
			'user-panel',
			'list',
			'image-hotspot',
			'products-tabs',
			'brands',
			'categories',
			'product-filters',
			'products',
			'products-widget',
		);

		$this->woo_shortcodes = array(
			'products-tabs',
			'brands',
			'categories',
			'product-filters',
			'products',
			'products-widget',
		);

		$this->vc_elements = array(
			'3d-view',
			'products-tabs',
			'ajax-search',
			'counter',
			'author-area',
			'promo-banner',
			'blog',
			'brands',
			'button',
			'countdown-timer',
			'extra-menu',
			'google-map',
			'image-hotspot',
			'images-gallery',
			'info-box',
			'list',
			'instagram',
			'mega-menu',
			'menu-price',
			'popup',
			'portfolio',
			'pricing-tables',
			'categories',
			'product-filters',
			'products',
			'responsive-text-block',
			'title',
			'row-divider',
			'slider',
			'social',
			'team-member',
			'testimonials',
			'timeline',
			'twitter',
			'products-widget',
			'video-poster',
			'parallax-scroll',
			'compare',
		);

		$this->register_classes = array(
			'notices',
			'options',
			'layout',
			'import',
			'vctemplates',
			'api',
			'license',
			'cssgenerator',
			'dynamiccss',
		);	

		$this->files_include = array(
			'helpers',
			'functions',
			'template-tags',
			'theme-setup',

			'widgets/widgets',

			//General modules
			'modules/lazy-loading',
			'modules/search',
			'modules/nav-menu-images/nav-menu-images',

			//Header builder
			'builder/Builder',
			'builder/Frontend',
			'builder/functions',

			//Woocommerce integration
			'integrations/woocommerce/functions',
			'integrations/woocommerce/helpers',
			'integrations/woocommerce/template-tags',

			//Woocommerce modules
			'integrations/woocommerce/modules/attributes-meta-boxes',
			'integrations/woocommerce/modules/product-360-view',
			'integrations/woocommerce/modules/size-guide',
			'integrations/woocommerce/modules/variation-gallery',
			'integrations/woocommerce/modules/swatches',
			'integrations/woocommerce/modules/catalog-mode',
			'integrations/woocommerce/modules/maintenance',
			'integrations/woocommerce/modules/progress-bar',
			'integrations/woocommerce/modules/quick-shop',
			'integrations/woocommerce/modules/quick-view',
			'integrations/woocommerce/modules/brands',
			'integrations/woocommerce/modules/compare',

			//Plugin integrations
			'integrations/wpml',
			'integrations/yith-add-to-quote',
			'integrations/yith-compare',
			'integrations/yith-wishlist',
			'integrations/dokan',
			'integrations/tgm-plugin-activation',

			//Visual composer integration
			'integrations/visual-composer/functions',
			'integrations/visual-composer/fields/vc-functions',
			'integrations/visual-composer/fields/image-hotspot',
			'integrations/visual-composer/fields/title-divider',
			'integrations/visual-composer/fields/slider',
			'integrations/visual-composer/fields/responsive-size',
			'integrations/visual-composer/fields/image-select',
			'integrations/visual-composer/fields/dropdown',
			'integrations/visual-composer/fields/css-id',
			'integrations/visual-composer/fields/gradient',
			'integrations/visual-composer/fields/colorpicker',
			'integrations/visual-composer/fields/datepicker',
			'integrations/visual-composer/fields/switch',
			'integrations/visual-composer/fields/button-set',
			'integrations/visual-composer/fields/empty-space',

			//CMB2
 			'admin/cmb2/cmb2-conditionals/cmb2-conditionals',
			'admin/cmb2/fields/images-select',
			'admin/cmb2/fields/slider',
			'admin/cmb2/fields/button-set',
			'admin/cmb2/metaboxes/pages',
			'admin/cmb2/metaboxes/products',
			'admin/cmb2/metaboxes/slider',
			'admin/cmb2/functions',

			//REDUX
			'admin/redux/settings/config',
			'admin/redux/fields/woodmart_gradient',
			'admin/redux/fields/woodmart_multi_fonts',
			'admin/redux/fields/woodmart_typography',
			'admin/redux/fields/woodmart_title',

		);	

		$this->admin_files_include = array(
			'builder/Builder',
			'builder/Backend',
			'admin/dashboard/dashboard',
			'admin/init',
			'admin/functions',
		);	

		$this->_core_plugin_classes();
		$this->_include_files();
		$this->_register_classes();

		$this->_include_vc_elements();
		$this->_include_shortcodes();

		if( is_admin() ) {
			$this->_include_admin_files();
		}

	}

	/**
	 * Register classes in WOODMART_Registry
	 * 
	 */
	private function _register_classes() {

		foreach ($this->register_classes as $class) {
			WOODMART_Registry::getInstance()->$class;
		}

	}

	/**
	 * Include files from inc/ folder
	 * 
	 */
	private function _include_files() {

		foreach ($this->files_include as $file) {
			$path = get_parent_theme_file_path( WOODMART_FRAMEWORK . '/' . $file . '.php');
			if( file_exists( $path ) ) {
				require_once $path;
			}
		}

	}

	/**
	 * Include files from inc/ vc-element
	 * 
	 */
	private function _include_vc_elements() {
		$vc_elements = $this->vc_elements;

		if ( ! woodmart_woocommerce_installed() ) {
			$vc_elements = array_diff( $this->vc_elements, $this->woo_shortcodes );
		}

		foreach ( $vc_elements as $file ) {
			$path = get_template_directory() . '/inc/integrations/visual-composer/maps/' . $file . '.php';
			if( file_exists( $path ) ) {
				require_once $path;
			}
		}

	}

	/**
	 * Include files from inc/ shortcodes
	 * 
	 */
	private function _include_shortcodes() {
		$shortcodes = $this->shortcodes;

		if ( ! woodmart_woocommerce_installed() ) {
			$shortcodes = array_diff( $this->shortcodes, $this->woo_shortcodes );
		}

		foreach ( $shortcodes as $file ) {
			$path = get_template_directory() . '/inc/shortcodes/' . $file . '.php';
			if( file_exists( $path ) ) {
				require_once $path;
			}
		}

	}

	/**
	 * Include files in admin area
	 * 
	 */
	private function _include_admin_files() {

		foreach ($this->admin_files_include as $file) {
			$path = get_parent_theme_file_path( WOODMART_FRAMEWORK . '/' . $file . '.php');
			if( file_exists( $path ) ) {
				require_once $path;
			}
		}

	}

	private function _core_plugin_classes() {
		if ( class_exists( 'WOODMART_Auth' ) ) {
			$file_path = array(
				'vendor/opauth/twitteroauth/twitteroauth',
				'vendor/autoload'
			);
			foreach ( $file_path as $file ) {
				$path = apply_filters('woodmart_require', WOODMART_PT_3D . $file . '.php');
				if( file_exists( $path ) ) {
					require_once $path;
				}
			}
			$this->register_classes[] = 'auth';
		}
	}

}