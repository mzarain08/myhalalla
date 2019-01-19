<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Widget with author info
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_shortcode_author_area' ) ) {
	function woodmart_shortcode_author_area($atts, $content) {
		if( ! function_exists( 'wpb_getImageBySize' ) ) return;
		$output = $class = '';
		extract( shortcode_atts( array(
			'title' => '',
			'author_name' => '',
			'image' => '',
			'img_size' => '800x600',
			'link' => '',
			'link_text' => '',
			'alignment' => 'left',
			'style' => '',
			'woodmart_color_scheme' => 'dark',
			'css_animation' => 'none',
			'el_class' => ''
		), $atts ) );

		$img_id = preg_replace( '/[^\d]/', '', $image );

		$img = wpb_getImageBySize( array( 'attach_id' => $img_id, 'thumb_size' => $img_size, 'class' => 'author-area-image' ) );

		$class .= ' text-' . $alignment;
		$class .= ' color-scheme-' . $woodmart_color_scheme;
		$class .= woodmart_get_css_animation( $css_animation );
		$class .= ' ' . $el_class;

		if( $link != '') {
			$attributes = woodmart_get_link_attributes( $link );
			$link = '<a ' . $attributes . '>' . esc_html( $link_text ) . '</a>';
		}

		ob_start(); ?>

			<div class="author-area<?php echo esc_attr( $class ); ?>">

				<?php if ( $title ): ?>
					<h3 class="title author-title">
						<?php echo esc_html( $title ); ?>
					</h3>
				<?php endif ?>

				<div class="author-avatar">
					<?php echo wp_kses( $img['thumbnail'], array( 'img' => array( 'class' => true, 'width' => true, 'height' => true, 'src' => true, 'alt' => true ) ) ); ?>
				</div>

				<?php if ( $author_name ): ?>
					<h4 class="title author-name">
						<?php echo esc_html( $author_name ); ?>
					</h4>
				<?php endif ?>
				
				<?php if ( $content ): ?>
					<div class="author-info">
						<?php echo do_shortcode( $content ); ?>
					</div>
				<?php endif ?>

				<?php echo ( $link ); ?>

			</div>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
	add_shortcode( 'author_area', 'woodmart_shortcode_author_area' );
}
