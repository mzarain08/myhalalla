<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Google Map shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_shortcode_google_map' ) ) {
	function woodmart_shortcode_google_map( $atts, $content ) {
		$output = '';
		$parsed_atts = shortcode_atts( array(
			'title' => '',
			'lat' => 45.9,
			'lon' => 10.9,
			'style_json' => '',
			'zoom' => 15,
			'height' => 400,
			'scroll' => 'no',
			'mask' => '',
			'marker_text' => '',
			'marker_content' => '',
			'content_vertical' => 'top',
			'content_horizontal' => 'left',
			'content_width' => 300,
			'google_key' => woodmart_get_opt( 'google_map_api_key' ),
			'marker_icon' => '',
			'css_animation' => 'none',
			'el_class' => ''
		), $atts );
		
		extract( $parsed_atts );

		wp_enqueue_script( 'google.map.api', 'https://maps.google.com/maps/api/js?sensor=false&libraries=geometry&v=3.22&key=' . $google_key . '', array(), '', false );
		wp_enqueue_script( 'maplace' );

		$el_class .= ' content-vertical-' . $content_vertical;
		$el_class .= ' content-horizontal-' . $content_horizontal;
		$el_class .= woodmart_get_css_animation( $css_animation );

		if( $mask != '' ) {
			$el_class .= ' map-mask-' . $mask;
		}

		$id = rand(100,999);
		
		$parsed_atts['marker_content'] = '<h3 style="min-width:300px; text-align:center; margin:15px;">' . $title . '</h3>' . $marker_text;
		
		ob_start();

		?>

			<?php if ( ! empty( $content ) ): ?>		
				<div class="google-map-container <?php echo esc_attr( $el_class ); ?> map-container-with-content" style="height:<?php echo esc_attr( $height ); ?>px;">

					<div class="woodmart-google-map-wrapper">
						<div class="woodmart-google-map with-content google-map-<?php echo esc_attr( $id ); ?>"></div>
					</div>
					<div class="woodmart-google-map-content-wrap container">
						<div class="woodmart-google-map-content" style="max-width: <?php echo esc_attr( $content_width ); ?>px;">
							<?php echo do_shortcode( $content ); ?>
						</div>
					</div>

				</div>
			<?php else: ?>

				<div class="google-map-container <?php echo esc_attr( $el_class );?>"  style="height:<?php echo esc_attr( $height ); ?>px;">

					<div class="woodmart-google-map-wrapper">
						<div class="woodmart-google-map without-content google-map-<?php echo esc_attr( $id ); ?>"></div>
					</div>

				</div>

			<?php endif ?>
		<?php
		wp_add_inline_script( 'woodmart-theme', woodmart_google_map_init_js( $parsed_atts, $id ), 'after' );
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
	add_shortcode( 'woodmart_google_map', 'woodmart_shortcode_google_map' );
}

if( ! function_exists( 'woodmart_google_map_init_js' ) ) {
	function woodmart_google_map_init_js( $atts, $id ) {
		$output = '';
		extract(shortcode_atts( array(
			'title' => '',
			'lat' => 45.9,
			'lon' => 10.9,
			'style_json' => '',
			'zoom' => 15,
			'scroll' => 'no',
			'marker_icon' => '',
			'marker_content' => ''
		), $atts ));

		if ( $marker_icon ) {
			$marker_url = wp_get_attachment_image_src( $marker_icon );
			$marker_icon = $marker_url[0];
		}else{
			$marker_icon = WOODMART_ASSETS . '/images/google-icon.png';
		}

		ob_start();
		?>
			jQuery(document).ready(function() {

				new Maplace({
					locations: [
					    {
							lat: <?php echo esc_js( $lat ); ?>,
							lon: <?php echo esc_js( $lon ); ?>,
							title: '<?php echo esc_js( $title ); ?>',
					        <?php if( $title != '' && empty( $content ) ): ?>
					        	html: <?php echo json_encode( $marker_content ); ?>, 
					        <?php endif; ?>
					        icon: '<?php echo esc_js( $marker_icon ); ?>',
					        animation: google.maps.Animation.DROP
					    }
					],
					controls_on_map: false,
					title: '<?php echo esc_js( $title ); ?>',
				    map_div: '.google-map-<?php echo esc_js( $id ); ?>',
				    start: 1,
				    map_options: {
				        zoom: <?php echo esc_js( $zoom ); ?>,
				        scrollwheel: <?php echo ($scroll == 'yes') ? 'true' : 'false'; ?>
				    },
				    <?php if($style_json != ''): ?>
				    styles: {
						'<?php esc_html_e('Custom style', 'woodmart') ?>': <?php 
								$styles = function_exists( 'woodmart_decompress' ) ? woodmart_decompress( $style_json, true ) : '{}';
								echo rawurldecode( $styles ); ?>
				    }
				    <?php endif; ?>
				}).Load();

			});
		<?php
		return ob_get_clean();
	}
}