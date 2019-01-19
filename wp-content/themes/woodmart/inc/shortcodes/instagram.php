<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Instagram shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_shortcode_instagram' ) ) {
	function woodmart_shortcode_instagram( $atts, $content = '' ) {
		$output = $pics_classes = $picture_classes = $owl_atts = '';
		extract(shortcode_atts( array(
			'title' => '',
			'username' => 'flickr',
			'number' => 9,
			'size' => 'medium',
			'target' => '_self',
			'link' => '',
			'design' => 'grid',
			'spacing' => 0,
			'spacing_custom' => 6,
			'rounded' => 0,
			'per_row' => 3,
			'hide_mask' => 0,
			'hide_pagination_control' => '',
			'hide_prev_next_buttons' => '',
			'el_class' => '',
		), $atts ));

		$carousel_id = 'carousel-' . rand(100,999);

		ob_start();

		$class = 'instagram-widget';

		$class .= $el_class ? ' ' . $el_class : '';

		if( $design != '' ) {
			$class .= ' instagram-' . $design;
		}

		if( $rounded == 1 ) {
			$class .= ' instagram-rounded';
		}

		if ( ! $spacing ) {
			$spacing_custom = 0;
		}

		if ( $design == 'slider' ) {
			$custom_sizes = apply_filters( 'woodmart_instagram_shortcode_custom_sizes', false );

			$owl_atts = woodmart_get_owl_attributes( array(
				'carousel_id' => $carousel_id,
				'hide_pagination_control' => $hide_pagination_control,
				'hide_prev_next_buttons' => $hide_prev_next_buttons,
				'slides_per_view' => $per_row,
				'custom_sizes' => $custom_sizes,
			) );

			if ( woodmart_get_opt( 'disable_owl_mobile_devices' ) ) {
				$class .= ' disable-owl-mobile';
			}

			$pics_classes .= ' owl-carousel ' . woodmart_owl_items_per_slide( $per_row, array(), false, false, $custom_sizes );
			$class .= ' woodmart-carousel-container';
			$class .= ' woodmart-carousel-spacing-' . $spacing_custom;
		} else {
			$pics_classes .= ' row';
			$picture_classes .= woodmart_get_grid_el_class( 0, $per_row );
			$pics_classes .= ' woodmart-spacing-' . $spacing_custom;
		}

		echo '<div id="' . esc_attr( $carousel_id ) . '" class="instagram-pics ' . esc_attr( $class ) . '" ' . $owl_atts . '>';

		if(!empty($title)) { echo '<h3 class="title">' . $title . '</h3>'; };

		if ($username != '') {

			if ( ! empty( $content ) ): ?>
				<div class="instagram-content">
					<div class="instagram-content-inner">
						<?php echo do_shortcode( $content ); ?>
					</div>
				</div>
			<?php endif;

			$media_array = woodmart_scrape_instagram($username, $number);

			if ( is_wp_error($media_array) ) {

			   echo esc_html( $media_array->get_error_message() );

			} else {
				?>
					<div class="<?php esc_attr_e( $pics_classes ); ?>">
					<?php
						foreach ($media_array as $item) {
							$image = (! empty( $item[$size] )) ? $item[$size] : $item['thumbnail'];
							$image_html = apply_filters('woodmart_image', '<img src="'. esc_url( $image ) .'" />' );
							$result = '<div class="instagram-picture' . esc_attr( $picture_classes ) . '">
								<div class="wrapp-picture">
									<a href="'. esc_url( $item['link'] ) .'" target="'. esc_attr( $target ) .'"></a>
									'. $image_html;
									if ( $hide_mask == 0 ) {
										$result .= '<div class="hover-mask">
										<span class="instagram-likes"><span>' . woodmart_pretty_number( $item['likes'] ) . '</span></span>
										<span class="instagram-comments"><span>' . woodmart_pretty_number( $item['comments'] ) . '</span></span></div>';
									}
							$result .= '
								</div>
							</div>';
							echo ( $result );
						}
					?>
					</div>
				<?php
			}
		}

		if ($link != '') {
			?><p class="clear"><a href="//instagram.com/<?php echo trim($username); ?>" rel="me" target="<?php echo esc_attr( $target ); ?>"><?php echo esc_html($link); ?></a></p><?php
		}

		echo '</div>';

		$output = ob_get_contents();
		ob_end_clean();

		return $output;

	}
	add_shortcode( 'woodmart_instagram', 'woodmart_shortcode_instagram' );
}

if( ! function_exists( 'woodmart_pretty_number' ) ) {
	function woodmart_pretty_number( $x = 0 ) {
		$x = (int) $x;

		if( $x > 1000000 ) {
			return floor( $x / 1000000 ) . 'M';
		}

		if( $x > 10000 ) {
			return floor( $x / 1000 ) . 'k';
		}
		return $x;
	}
}

if( ! function_exists( 'woodmart_scrape_instagram' ) ) {
	function woodmart_scrape_instagram( $username, $slice = 9 ) {
		$username = strtolower( $username );
		$by_hashtag = ( substr( $username, 0, 1 ) == '#' );
		$transient_name = 'instagram-media-new-' . sanitize_title_with_dashes( $username );
		$instagram = get_transient( $transient_name );
		
		if ( false === $instagram ) {
			
			$request_param = ( $by_hashtag ) ? 'explore/tags/' . substr( $username, 1 ) : trim( $username );
			$remote = wp_remote_get( 'https://instagram.com/'. $request_param );
			
			if ( is_wp_error( $remote ) ) {
				return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'woodmart' ) );
			}

			if ( 200 != wp_remote_retrieve_response_code( $remote ) ) {
				return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'woodmart' ) );
			}
				
			$shards = explode( 'window._sharedData = ', $remote['body'] );
			$insta_json = explode( ';</script>', $shards[1] );
			$insta_array = json_decode( $insta_json[0], TRUE );

			if ( ! $insta_array ){
				return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'woodmart' ) );
			}
				
			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
			} elseif( $by_hashtag && isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
		        $images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
		    } else {
				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'woodmart' ) );
			}

			if ( ! is_array( $images ) ) {
				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'woodmart' ) );
			}
				
			$instagram = array();
			
			foreach ( $images as $image ) {
				$image = $image['node'];
				$caption = esc_html__( 'Instagram Image', 'woodmart' );
				if ( ! empty( $image['edge_media_to_caption']['edges'][0]['node']['text'] ) ) $caption = $image['edge_media_to_caption']['edges'][0]['node']['text'];

				$image['thumbnail_src'] = preg_replace( "/^https:/i", "", $image['thumbnail_src'] );
				$image['thumbnail'] = preg_replace( "/^https:/i", "", $image['thumbnail_resources'][0]['src'] );
				$image['medium'] = preg_replace( "/^https:/i", "", $image['thumbnail_resources'][2]['src'] );
				$image['large'] = $image['thumbnail_src'];
				
				$type = ( $image['is_video'] ) ? 'video' : 'image';
				
				$instagram[] = array(
					'description'   => $caption,
					'link'		  	=> '//instagram.com/p/' . $image['shortcode'],
					'comments'	  	=> $image['edge_media_to_comment']['count'],
					'likes'		 	=> $image['edge_liked_by']['count'],
					'thumbnail'	 	=> $image['thumbnail'],
					'medium'		=> $image['medium'],
					'large'			=> $image['large'],
					'type'		  	=> $type
				);
			}
		
			// do not set an empty transient - should help catch private or empty accounts
			if ( ! empty( $instagram ) ) {
				$instagram = function_exists( 'woodmart_compress' ) ? woodmart_compress( maybe_serialize( $instagram ) ) : '';
				set_transient( $transient_name, $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
			}
		}
		if ( ! empty( $instagram ) ) {
			$instagram = function_exists( 'woodmart_decompress' ) ? maybe_unserialize( woodmart_decompress( $instagram ) ) : array();
			return array_slice( $instagram, 0, $slice );
		} else {
			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'woodmart' ) );
		}
	}
}

if( !function_exists( 'woodmart_instagram_images_only' ) ) {
	function woodmart_instagram_images_only($media_item) {
		if ($media_item['type'] == 'image')
			return true;

		return false;
	}
}