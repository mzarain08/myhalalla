<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Color and Images swatches for WooCommerce products attributes
 */

if( ! function_exists( 'woodmart_swatches_metaboxes' ) ) {
	function woodmart_swatches_metaboxes(	) {
		if( ! function_exists('wc_get_attribute_taxonomies') ) return;
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		foreach ($attribute_taxonomies as $key => $value) {

			$fields = array(
				array(
                    'name' => 'Enable swatch',
                    'desc' => 'Attribute dropdown will be replaces with squared buttons',
                    'id' => 'not_dropdown',
                    'type' => 'checkbox'
            	),
				array(
                    'name' => 'Image preview for this value',
                    'desc' => 'Upload an image',
                    'id' => 'image',
		            'type' => 'file',
		            'allow' => array( 'url', 'attachment' ) // limit to just attachments with array( 'attachment' )
            	),
				array(
                    'name' => 'Color preview for this value',
                    'desc' => 'Select color',
                    'id' => 'color',
                    'type' => 'colorpicker'
            	),
			);

			$cmb_term = new_cmb2_box( array(
				'id'               => 'pa_fields_' . $value->attribute_name,
				'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
				'taxonomies'       => array( 'pa_' . $value->attribute_name ), // Tells CMB2 which taxonomies should have these fields
				// 'new_term_section' => true, // Will display in the "Add New Category" section
			) );

			foreach ($fields as $field) {
				$cmb_term->add_field($field);
			}
			
		}
	}

	add_action( 'cmb2_init', 'woodmart_swatches_metaboxes', 299 );
}

if( ! function_exists( 'woodmart_has_swatches' ) ) {
	function woodmart_has_swatches( $id, $attr_name, $options, $available_variations, $swatches_use_variation_images = false ) {
		$swatches = array();

		foreach ($options as $key => $value) {
			$swatch = woodmart_has_swatch($id, $attr_name, $value);

			if( ! empty( $swatch ) ) {

				if( $available_variations && $swatches_use_variation_images && woodmart_get_opt( 'grid_swatches_attribute' ) == $attr_name ) {

					$variation = woodmart_get_option_variations( $attr_name, $available_variations, $value );

					$swatch = array_merge( $swatch, $variation);
				}

				$swatches[$key] = $swatch;
			}
		}

		return $swatches;
	}
}

if( ! function_exists( 'woodmart_has_swatch' ) ) {
	function woodmart_has_swatch($id, $attr_name, $value) {
		$swatches = array();

		$color = $image = $not_dropdown = '';
		
		$term = get_term_by( 'slug', $value, $attr_name );
		if ( is_object( $term ) ) {
			$color = get_term_meta( $term->term_id, 'color', true );
			$image = get_term_meta( $term->term_id, 'image', true );
			$not_dropdown = get_term_meta( $term->term_id, 'not_dropdown', true );
		}
		
		if( $color != '' ) {
			$swatches['color'] = $color;
		}

		if( $image != '' ) {
			$swatches['image'] = $image;
		}

		if( $not_dropdown != '' ) {
			$swatches['not_dropdown'] = $not_dropdown;
		}

		return $swatches;
	}
}

if( ! function_exists( 'woodmart_get_option_variations' ) ) {
	function woodmart_get_option_variations( $attribute_name, $available_variations, $option = false, $product_id = false ) {
		$swatches_to_show = array();
		foreach ($available_variations as $key => $variation) {
			$option_variation = array();
			$attr_key = 'attribute_' . $attribute_name;
			if( ! isset( $variation['attributes'][$attr_key] )) return;

			$val = $variation['attributes'][$attr_key]; // red green black ..

			if( ! empty( $variation['image']['src'] ) ) {
				$option_variation = array(
					'variation_id' => $variation['variation_id'],
					'image_src' => $variation['image']['src'],
					'image_srcset' => $variation['image']['srcset'],
					'image_sizes' => $variation['image']['sizes'],
					'is_in_stock' => $variation['is_in_stock'],
				);
			}

			// Get only one variation by attribute option value 
			if( $option ) {
				if( $val != $option ) {
					continue;
				} else {
					return $option_variation;
				}
			} else {
				// Or get all variations with swatches to show by attribute name
				
				$swatch = woodmart_has_swatch($product_id, $attribute_name, $val);
				$swatches_to_show[$val] = array_merge( $swatch, $option_variation);

			}

		}

		return $swatches_to_show;

	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Show attribute swatches list
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'woodmart_swatches_list' ) ) {
	function woodmart_swatches_list( $attribute_name = false ) {
		global $product;

		$id = $product->get_id();

		if( empty( $id ) || ! $product->is_type( 'variable' ) ) return false;
		
		if( ! $attribute_name ) {
			$attribute_name = woodmart_grid_swatches_attribute();
		}
		
		if( empty( $attribute_name ) ) return false;

		$available_variations = $product->get_available_variations();

		if( empty( $available_variations ) ) return false;

		$swatches_to_show = woodmart_get_option_variations(  $attribute_name, $available_variations, false, $id );

		if( empty( $swatches_to_show ) ) return false;
		$out = '';

		$out .=  '<div class="swatches-on-grid">';

		$swatch_size = woodmart_wc_get_attribute_term( $attribute_name, 'swatch_size' );

		if( apply_filters( 'woodmart_swatches_on_grid_right_order', true ) ) {
			$terms = wc_get_product_terms( $product->get_id(), $attribute_name, array( 'fields' => 'slugs' ) );

			$swatches_to_show_tmp = $swatches_to_show;

			$swatches_to_show = array();

			foreach ($terms as $id => $slug) {
				//Fixed php notice
				if ( ! isset( $swatches_to_show_tmp[$slug] ) ) continue;
				$swatches_to_show[$slug] = $swatches_to_show_tmp[$slug];
			}
		}

		foreach ($swatches_to_show as $key => $swatch) {
			$style = $class = '';

			if( ! empty( $swatch['color'] )) {
				$style = 'background-color:' .  $swatch['color'];
			} else if( ! empty( $swatch['image'] ) ) {
				$style = 'background-image: url(' . $swatch['image'] . ')';
			} else if( ! empty( $swatch['not_dropdown'] ) ) {
				$class .= 'text-only ';
			}

			$style .= ';';

			$data = '';

			if( isset( $swatch['image_src'] ) ) {
				$class .= 'swatch-has-image';
				$data .= 'data-image-src="' . $swatch['image_src'] . '"';
				$data .= ' data-image-srcset="' . $swatch['image_srcset'] . '"';
				$data .= ' data-image-sizes="' . $swatch['image_sizes'] . '"';
				if( woodmart_get_opt( 'swatches_use_variation_images' ) ) {
					$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $swatch['variation_id'] ), 'woocommerce_thumbnail');
					if ( !empty( $thumb ) ) {
						$style = 'background-image: url(' . $thumb[0] . ')';
						$class .= ' variation-image-used';
					}
				}

				if( ! $swatch['is_in_stock'] ) {
					$class .= ' variation-out-of-stock';
				}
			}

			$class .= ' swatch-size-' . $swatch_size;

			$term = get_term_by( 'slug', $key, $attribute_name );

			$out .=  '<div class="swatch-on-grid woodmart-tooltip ' . esc_attr( $class ) . '" style="' . esc_attr( $style ) .'" ' . $data . '>' . $term->name . '</div>';
		}

		$out .=  '</div>';

		return $out;

	}
}