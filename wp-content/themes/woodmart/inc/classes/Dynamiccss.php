<?php

if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
};

/**
 * Generate REDUX dynamic css file
 *
 */

class WOODMART_Dynamiccss {

	public function __construct() {
		$this->_notices = WOODMART_Registry()->notices;
		add_filter( 'redux/options/woodmart_options/compiler', array( $this, 'save_css' ), 10, 3 );
		add_filter( 'redux/page/woodmart_options/load', array( $this , 'write_file' ), 10, 3 );
		
		add_action( 'wp', array( $this, 'dynamic_css' ), 100 );
	}

	public function dynamic_css() {
		$file = get_option( 'woodmart-dynamic-css-file' );
		
		if ( isset( $file['path'] ) && file_exists( $file['path'] ) && get_option( 'woodmart-file-status' ) == 'valid' && apply_filters( 'woodmart_dynamic_css', true ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'file_css' ), 100001 );
		} else {
			add_action( 'wp_head', array( $this, 'inline_css' ), 100 );
		}
	}
		
	public function file_css() {
		$file = get_option( 'woodmart-dynamic-css-file' );

		if ( isset( $file['url'] ) ) {
			if ( is_ssl() ) {
				$file['url'] = str_replace( 'http://', 'https://', $file['url'] );
			}
			wp_enqueue_style( 'woodmart-dynamic-style', $file['url'], array( 'bootstrap' ), woodmart_get_theme_info( 'Version' ) );
		}
	}

	public function inline_css() {
		$css = '';

		if ( apply_filters( 'woodmart_dynamic_css', true ) ) {
			$css .= get_option( 'woodmart-dynamic-css-data' );
		}
		
		$css .= $this->custom_css();
		$css .= $this->customFontsCss();

		if ( $css ) {
			echo '<style type="text/css" data-type="woodmart-dynamic-css">' . $css . '</style>';
		}
	}

	public function file_info( $target ) {
		$file_name = 'dynamic-' . time() . '.css';
		$uploads = wp_upload_dir();

		if ( 'filename' == $target ) {
			return $file_name;
		}

		if ( 'path' == $target ) {
			return $uploads['path'] . '/' . $file_name;
		}

		return $uploads['url'] . '/' . $file_name;
	}

	public function write_file() {

		if ( ! apply_filters( 'woodmart_dynamic_css', true ) || ! $this->check_credentials() ) {
			return;
		}

		global $wp_filesystem;

		if ( ! $wp_filesystem ) {
			return;
		}

		$file = get_option( 'woodmart-dynamic-css-file' );

		if ( $file && $file['path'] ) {
			$wp_filesystem->delete( $file['path'] );
			delete_option( 'woodmart-dynamic-css-file' );
		}

		$css = get_option( 'woodmart-dynamic-css-data' );

		if ( ! $css ) {
			return;
		}

		$css .= $this->custom_css();
		$css .= $this->customFontsCss();

		$result = $wp_filesystem->put_contents(
			$this->file_info( 'path' ),
			$css
		);

		if ( $result ) {
			update_option( 'woodmart-dynamic-css-file', array(
				'url' => $this->file_info( 'url' ),
				'path' => $this->file_info( 'path' ),
			) );
			update_option( 'woodmart-file-status', 'valid' );
		} else {
			$this->_notices->add_warning( 'Can\'t move file to uploads folder with wp_filesystem class.' );
			$this->_notices->show_msgs();
		}
	}

	public function save_css( $options, $css, $changed_values ) {
		update_option( 'woodmart-dynamic-css-data', $css );
		update_option( 'woodmart-file-status', 'invalid' );
		delete_option( 'woodmart-file-credentials' );
	}

	public function check_credentials() {
		global $wp_filesystem;

		$file_status = get_option( 'woodmart-file-status' );
		$credentials = get_option( 'woodmart-file-credentials' );
		
		if ( ( $file_status == 'valid' || $credentials == 'requested' ) && ! $_POST ) {
			return false;
		}

		update_option( 'woodmart-file-credentials', 'requested' );

		echo '<div class="woodmart-request-credentials">';
			if ( ! $_POST && $wp_filesystem->method != 'direct' ) {
				$this->_notices->add_warning( 'Fill and submit this form to update dynamic CSS file with new options on your server. Or just skip it and CSS code will be injected in your website HTML code and not as an external file.' );
				$this->_notices->show_msgs();
			}
			$creds = request_filesystem_credentials( false, '', false, false, array_keys( $_POST ) );
		echo '</div>';

		if ( ! $creds ) {
			return false;
		}

		if ( ! WP_Filesystem( $creds ) ) {
			$this->_notices->add_warning( 'Can\'t access your file system. The FTP access is wrong.' );
			$this->_notices->show_msgs();
			return false;
		}

		return true;
	}

	public function custom_css() {
		$output = '';
		$custom_css      = woodmart_get_opt( 'custom_css' );
		$css_desktop     = woodmart_get_opt( 'css_desktop' );
		$css_tablet      = woodmart_get_opt( 'css_tablet' );
		$css_wide_mobile = woodmart_get_opt( 'css_wide_mobile' );
		$css_mobile      = woodmart_get_opt( 'css_mobile' );

		if ( $custom_css ) {
			$output .= $custom_css;
		}
		
		if ( $css_desktop ) {
			$output .= '@media (min-width: 1025px) { ' . $css_desktop . ' }';
		}
		
		if ( $css_tablet ) {
			$output .= '@media (min-width: 768px) and (max-width: 1024px) {' . $css_tablet . ' }';
		}
		
		if ( $css_wide_mobile ) {
			$output .= '@media (min-width: 577px) and (max-width: 767px) { ' . $css_wide_mobile . ' }';
		}
		
		if ( $css_mobile ) {
			$output .= '@media (max-width: 576px) { ' . $css_mobile . ' }';
		}

		return $output;
	}

	public function customFontsCss() {
		$output = '';

		$custom_fonts = woodmart_get_opt( 'multi_custom_fonts' );

		if ( ! $custom_fonts ) {
			return $output;
		}

		foreach ( $custom_fonts as $key => $value ) {
			$eot = $this->woodmart_get_custom_font_url( $value['font-eot'] );
			$woff = $this->woodmart_get_custom_font_url( $value['font-woff'] );
			$woff2 = $this->woodmart_get_custom_font_url( $value['font-woff2'] );
			$ttf = $this->woodmart_get_custom_font_url( $value['font-ttf'] );
			$svg = $this->woodmart_get_custom_font_url( $value['font-svg'] );

			if ( ! $value['font-name'] ) {
				continue;
			}

			$output .= '@font-face {';
				$output .= 'font-family: "'. sanitize_text_field( $value['font-name'] ) .'";';
				if ( $eot ) {
					$output .= 'src: url("'. esc_url( $eot ) .'");';
					$output .= 'src: url("'. esc_url( $eot ).'#iefix") format("embedded-opentype"),';
				}

				if ( $woff ) {
					$output .= 'url("'. esc_url( $woff ) .'") format("woff"),';
				}

				if ( $woff2 ) {
					$output .= 'url("'. esc_url( $woff2 ) .'") format("woff2"),';
				}

				if ( $ttf ) {
					$output .= 'url("'. esc_url( $ttf ) .'") format("truetype"),';
				}

				if ( $svg ) {
					$output .= 'url("'. esc_url( $svg ) .'#'. sanitize_text_field( $value['font-name'] ) .'") format("svg");';
				}

				if ( $value['font-weight'] ) {
					$output .= 'font-weight: ' . sanitize_text_field( $value['font-weight'] ) . ';';
				} else {
					$output .= 'font-weight: normal;';
				}

				$output .= 'font-style: normal;';
			$output .= '}';
		}

		return $output;
	}

	public function woodmart_get_custom_font_url( $font ) {
		$url = $font;
		if ( isset( $font['id'] ) && $font['id'] ) {
			$url = wp_get_attachment_url( $font['id'] );
		} elseif ( is_array( $font ) ) {
			$url = $font['url'];
		}

		return $url;
	}

}