<?php 
/**
 * Slider field for CMB2 plugin.
 */

if( ! function_exists( 'woodmart_cmb_slider_field' ) ) {
	function woodmart_cmb_slider_field( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {

		echo '<div class="woodmart-slider-field"></div>';

		echo $field_type_object->input( array(
			'type'       => 'hidden',
			'class'      => 'woodmart-slider-field-value',
			'readonly'   => 'readonly',
			'data-start' => absint( $field_escaped_value ),
			'data-min'   => $field->min(),
			'data-max'   => $field->max(),
			'data-step'  => $field->step(),
			'desc'       => '',
		) );

		echo '<span class="woodmart-slider-field-value-display">'. $field->value_label() .' <span class="woodmart-slider-field-value-text"></span></span>';

		$field_type_object->_desc( true, true );
	}

	add_filter( 'cmb2_render_woodmart_slider',  'woodmart_cmb_slider_field', 10, 5 );
}
