<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );	
}

/**
* Button set
*/
if ( ! function_exists( 'woodmart_cmb2_button_set_param' ) ) {
	function woodmart_cmb2_button_set_param( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {
		$output = '<div class="woodmart-cmb2-button-set">';

			$output .= '<ul class="woodmart-cmb2-button-set-list">';
				foreach ( $field->buttons_opts() as $key => $value ) {
					$active = $field_escaped_value == $key ? ' active' : '';
					$output .= '<li class="cmb2-button-set-item' . esc_attr( $active ) . '" data-value="' . esc_html( $key ) . '"><span>' . esc_html( $value ) . '</span></li>';
				}
			$output .= '</ul>';

			$field_type_object->_desc( true, true );

			$output .= $field_type_object->input( array(
				'type'       => 'hidden',
				'class'      => 'woodmart-cmb2-button-set-input',
				'readonly'   => 'readonly',
				'data-value' => $field_escaped_value,
				'desc'       => '',
			) );

		$output .= '</div>';

		echo $output;
	}

	add_filter( 'cmb2_render_woodmart_button_set', 'woodmart_cmb2_button_set_param', 10, 5 );
}