<?php 
/**
 * Images select field for CMB2 plugin.
 */

if( ! function_exists( 'woodmart_cmb_images_select_field' ) ) {
	function woodmart_cmb_images_select_field( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {

		echo '<div class="woodmart-images-select-field">';

			echo '<div class="woodmart-images-opts">';

			foreach ($field->images_opts() as $key => $value) {
				$active = (  $field_escaped_value == $key ) ? ' active' : '';
				echo '<div class="woodmart-image-opt' . $active . '" data-val="' . $key . '">
						<img src="' . $value['image'] . '" /><span>' . $value['label'] . '</span>
					</div>';
			}

			echo '</div>';

			echo $field_type_object->input( array(
				'type'       => 'hidden',
				'class'      => 'woodmart-vc-image-select-input',
				'readonly'   => 'readonly',
				'data-value' => $field_escaped_value,
				'desc'       => '',
			) );

		echo '</div>';

		$field_type_object->_desc( true, true );
	}

	add_filter( 'cmb2_render_woodmart_images_select',  'woodmart_cmb_images_select_field', 10, 5 );
}