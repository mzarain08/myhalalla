<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
 * Class to work with Theme Options
 * Will modify global $woodmart_options variable
 * 
 */
class WOODMART_Options {

	public function __construct() {

		$options = get_option('woodmart_options');

	    if ( ! class_exists( 'Redux' ) || ! $options || empty( $options ) ) {
	        $this->_load_base_options();
	    }

		if( ! is_admin() ) {

			add_action( 'wp', array( $this, 'set_custom_meta_for_post' ), 10 );
			add_action( 'wp', array( $this, 'set_options_for_post' ), 20 );
			add_action( 'wp', array( $this, 'specific_options' ), 30 );
			add_action( 'wp', array( $this, 'specific_taxonomy_options' ), 40 );

		}
	}

	/**
	 * Load basic options if redux is not installed
	 */
	private function _load_base_options() {
		global $woodmart_options;

		$woodmart_options = woodmart_get_config( 'base-options' );

	}

	/**
	 * Specific options
	 */
	public function set_options_for_post($slug = '') {
		global $woodmart_options;

		$custom_options = json_decode( get_post_meta( woodmart_page_ID(), '_woodmart_options', true), true );

		if( ! empty($custom_options) ) {
			$woodmart_options = wp_parse_args( $custom_options, $woodmart_options ); 
		}

		$woodmart_options = apply_filters( 'woodmart_global_options', $woodmart_options );

	}

		
	/**
	 * [set_custom_meta_for_post description]
	 */
	public function set_custom_meta_for_post($slug = '') {
		global $woodmart_options, $woodmart_transfer_options, $woodmart_prefix;
		if( ! empty( $woodmart_transfer_options ) ) {
			foreach ($woodmart_transfer_options as $field) {
				$meta = get_post_meta( woodmart_page_ID(), $woodmart_prefix . $field, true );
				if ( isset( $woodmart_options[$field] ) ) {
					$woodmart_options[$field] = ( isset($meta) && $meta != '' && $meta != 'inherit' && $meta != 'default' ) ? $meta : $woodmart_options[$field];
				}
			}
		}

	}


	/**
	 * Specific options dependencies
	 */
	public function specific_options($slug = '') {
		global $woodmart_options;

		$rules = woodmart_get_config( 'specific-options' );

		foreach ($rules as $option => $rule) {
			if( ! empty( $rule['will-be'] ) && ! isset( $rule['if'] )) {
				$woodmart_options[ $option ] = $rule['will-be'];
			} elseif( isset($woodmart_options[ $rule['if'] ]) && in_array( $woodmart_options[ $rule['if'] ], $rule['in_array'] ) ) {
				$woodmart_options[ $option ] = $rule['will-be'];
			}
		}

	}


	/**
	 * Specific options for taxonomies
	 */
	public function specific_taxonomy_options($slug = '') {
		global $woodmart_options;

		if ( is_category() ) {
			$option_key = 'blog_design';
			$category = get_query_var( 'cat' );
			$current_category = get_category( $category );
			//$current_category->term_id;
				
			$category_blog_design = get_term_meta( $current_category->term_id, '_woodmart_' . $option_key, true );

			if( ! empty($category_blog_design) && $category_blog_design != 'inherit' ) {
				$woodmart_options[ $option_key ] = $category_blog_design;
			}
		 }

	}


	/**
	 * Get option from Redux array $woodmart_options
	 * @param  String option slug
	 * @return String option value
	 */
	public function get_opt( $slug ) {
		global $woodmart_options;

		return isset($woodmart_options[$slug]) ? $woodmart_options[$slug] : '';
	}

}

// **********************************************************************// 
// ! Function to get option from Redux Framework
// **********************************************************************// 
if( ! function_exists( 'woodmart_get_opt' ) ) {
	function woodmart_get_opt($slug = '') {
		return WOODMART_Registry::getInstance()->options->get_opt( $slug );
	}
}