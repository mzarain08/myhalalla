<?php 
/**
* ------------------------------------------------------------------------------------------------
* Products widget shortcode
* ------------------------------------------------------------------------------------------------
*/
class WOODMART_ShortcodeProductsWidget{
	
	function __construct(){
		add_shortcode( 'woodmart_shortcode_products_widget', array( $this, 'woodmart_shortcode_products_widget' ) );
	}

	public function add_category_order($query_args){
		$ids = explode( ',', $this->ids );
		if ( !empty( $ids[0] ) ) {
			$query_args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'id',
				'terms'    => $ids,
			);
		}
		return $query_args;
	}

	public function add_product_order( $query_args ){
		$ids = explode( ',', $this->include_products );

		if ( ! empty( $ids[0] ) ) {
			$query_args['post__in'] = $ids;
			$query_args['orderby'] = 'post__in';
			$query_args['posts_per_page'] = -1;
		}
		
		return $query_args;
	}

	public function woodmart_shortcode_products_widget( $atts ){
		global $woodmart_widget_product_img_size;
		$output = $title = $el_class = '';
		extract( shortcode_atts( array(
			'title' => __( 'Products', 'woodmart' ),
			'ids' => '',
			'include_products' => '',
			'images_size' => 'woocommerce_thumbnail',
			'el_class' => ''
		), $atts ) );
		
		$woodmart_widget_product_img_size = $images_size;
		$this->ids = $ids;
		$this->include_products = $include_products;
		$output = '<div class="widget_products' . $el_class . '">';
		$type = 'WC_Widget_Products';

		$args = array('widget_id' => uniqid());

		ob_start();

		add_filter( 'woocommerce_products_widget_query_args', array( $this, 'add_category_order' ), 10 );
		add_filter( 'woocommerce_products_widget_query_args', array( $this, 'add_product_order' ), 20 );

		if ( function_exists( 'woodmart_woocommerce_installed' ) && woodmart_woocommerce_installed() ) {
			the_widget( $type, $atts, $args );
		}

		remove_filter( 'woocommerce_products_widget_query_args', array( $this, 'add_category_order' ), 10 );
		remove_filter( 'woocommerce_products_widget_query_args', array( $this, 'add_product_order' ), 20 );

		$output .= ob_get_clean();

		$output .= '</div>';

		unset( $woodmart_widget_product_img_size );

		return $output;

	}
}
$woodmart_shortcode_products_widget = new WOODMART_ShortcodeProductsWidget();