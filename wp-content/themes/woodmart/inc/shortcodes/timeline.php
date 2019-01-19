<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Timeline shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_timeline_shortcode' ) ) {
	function woodmart_timeline_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'line_color' 	 => '#e1e1e1',
			'dots_color' 	 => '#1e73be',
			'line_style' 	 => 'default',
			'item_style' 	 => 'default',
			'el_class' 	 => '',
			'woodmart_css_id' => '',
		), $atts) );
		
		if ( ! $woodmart_css_id ) $woodmart_css_id = uniqid();
		$timeline_id = 'wd-' . $woodmart_css_id;

		$classes = 'woodmart-timeline-wrapper';
		$classes .= ' woodmart-item-' . $item_style;
		$classes .= ' woodmart-line-' . $line_style;
		$classes .= $el_class ?  ' ' . $el_class : '';

		ob_start();
		?>
		<div id="<?php echo esc_attr( $timeline_id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
			<?php if ( $line_color || $dots_color ): ?>
				<style>
					<?php if ( $dots_color && ! woodmart_is_css_encode( $dots_color ) ): ?>
						#<?php echo esc_attr ( $timeline_id ); ?> .woodmart-timeline-dot {
							background-color: <?php echo esc_attr( $dots_color ); ?>;
						}
					<?php endif; ?>

					<?php if ( $line_color && ! woodmart_is_css_encode( $line_color ) ): ?>
						#<?php echo esc_attr ( $timeline_id ); ?> .dot-start,
						#<?php echo esc_attr ( $timeline_id ); ?> .dot-end {
							background-color: <?php echo esc_attr( $line_color ); ?>;
						}
						#<?php echo esc_attr ( $timeline_id ); ?> .woodmart-timeline-line {
							border-color: <?php echo esc_attr( $line_color ); ?>;
						}
					<?php endif; ?>
				/* */
				</style>
			<?php endif; ?>
			<div class="woodmart-timeline-line">
				<span class="line-dot dot-start"></span>
				<span class="line-dot dot-end"></span>
			</div>
			<div class="woodmart-timeline">
				<?php echo do_shortcode( $content ); ?>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}
	add_shortcode( 'woodmart_timeline', 'woodmart_timeline_shortcode' );
}

/**
* ------------------------------------------------------------------------------------------------
* Timeline item shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_timeline_item_shortcode' ) ) {
	function woodmart_timeline_item_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'title_primary' 	 => '',
			'image_primary' => '',
			'img_size_primary' => 'full',
			'title_secondary' 	 => '',
			'content_secondary' 	 => '',
			'image_secondary' => '',
			'img_size_secondary' => 'full',
			'position' 	 => 'left',
			'color_bg'   => '',
			'el_class' 	 => '',
			'woodmart_css_id' => '',
		), $atts) );

		$classes = 'woodmart-timeline-item';
		$classes .= ' woodmart-item-position-' . $position;

		$img_primary = $img_secondary = '';

		if ( ! $woodmart_css_id ) $woodmart_css_id = uniqid();
		$id = 'wd-' . $woodmart_css_id;
		
		if ( function_exists( 'wpb_getImageBySize' ) ) {
			$img_secondary = wpb_getImageBySize( array( 'attach_id' => $image_secondary, 'thumb_size' => $img_size_secondary ) );
			$img_primary = wpb_getImageBySize( array( 'attach_id' => $image_primary, 'thumb_size' => $img_size_primary ) );
		}

		( $el_class != '' ) ? $classes .= ' ' . $el_class : false ;
		ob_start();
		?>
		<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $classes ); ?>">

			<div class="woodmart-timeline-dot"></div>

			<div class="timeline-col timeline-col-primary">
				<span class="timeline-arrow"></span>
				<?php if ( $image_primary ): ?>
					<div class="woodmart-timeline-image" >
						<?php echo $img_primary['thumbnail']; ?>
					</div>
				<?php endif ?>
				<h4 class="woodmart-timeline-title"><?php echo esc_attr( $title_primary ); ?></h4> 
				<div class="woodmart-timeline-content reset-mb-10"><?php echo do_shortcode( $content ); ?></div>
			</div>

			<div class="timeline-col timeline-col-secondary">	
				<span class="timeline-arrow"></span>
				<?php if ( $image_secondary ): ?>
					<div class="woodmart-timeline-image" >
						<?php echo $img_secondary['thumbnail']; ?>
					</div>
				<?php endif ?>
				<h4 class="woodmart-timeline-title"><?php echo esc_attr( $title_secondary ); ?></h4> 
				<div class="woodmart-timeline-content reset-mb-10"><?php echo do_shortcode( $content_secondary ); ?></div>
			</div>

			<?php if ( $color_bg && ! woodmart_is_css_encode( $color_bg ) ): ?>
				<style>
					#<?php echo esc_attr ( $id ); ?>,
					#<?php echo esc_attr ( $id ); ?> .timeline-col-primary,
					#<?php echo esc_attr ( $id ); ?> .timeline-col-secondary{
						background-color: <?php echo esc_attr( $color_bg ); ?>;
					}

					#<?php echo esc_attr ( $id ); ?> .timeline-arrow{
						color: <?php echo esc_attr( $color_bg ); ?>;
					}
				</style>
			<?php endif; ?>

		</div>
		<?php

		return  ob_get_clean();
	}
	add_shortcode( 'woodmart_timeline_item', 'woodmart_timeline_item_shortcode' );
}

/**
* ------------------------------------------------------------------------------------------------
* Timeline breakpoint shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_timeline_breakpoint_shortcode' ) ) {
	function woodmart_timeline_breakpoint_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'title' 	 => '',
			'color_bg'      => '',
			'el_class' 	 => '',
			'woodmart_css_id' => '',
		), $atts) );

		$classes = 'woodmart-timeline-breakpoint';

		if ( ! $woodmart_css_id ) $woodmart_css_id = uniqid();
		$id = 'wd-' . $woodmart_css_id;

		( $el_class != '' ) ? $classes .= ' ' . $el_class : false ;
		ob_start();
		?>
		<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
			<span class="woodmart-timeline-breakpoint-title"><?php echo esc_attr( $title ); ?></span> 
			<?php if ( $color_bg && ! woodmart_is_css_encode( $color_bg ) ): ?>
				<style>
					#<?php echo esc_attr ( $id ); ?> .woodmart-timeline-breakpoint-title {
						background-color: <?php echo esc_attr( $color_bg ); ?>;
					}
				</style>
			<?php endif; ?>
		</div>
		<?php

		return  ob_get_clean();
	}
	add_shortcode( 'woodmart_timeline_breakpoint', 'woodmart_timeline_breakpoint_shortcode' );
}