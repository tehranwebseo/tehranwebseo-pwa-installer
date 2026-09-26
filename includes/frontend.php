<?php
defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', 'fpwai_enqueue' );
function fpwai_enqueue() {
	$settings = fpwai_settings();
	if ( is_admin() || is_feed() || is_embed() || ! $settings['enabled'] ) {
		return;
	}
	if ( ! $settings['floating'] && '' === $settings['trigger_id'] && ! fpwai_manages_pwa() ) {
		return;
	}
	wp_enqueue_style( 'fpwai', FPWAI_URL . 'assets/css/pwa-button.css', array(), FPWAI_VERSION );
	wp_enqueue_script( 'fpwai', FPWAI_URL . 'assets/js/pwa-install.js', array(), FPWAI_VERSION, false );
	$css = '#fpwai-button{--fpwai-bg:' . $settings['background'] . ';--fpwai-fg:' . $settings['foreground'] . ';--fpwai-hover-bg:' . $settings['hover_background'] . ';--fpwai-hover-fg:' . $settings['hover_foreground'] . ';border-radius:' . $settings['radius'] . 'px;' . fpwai_position_css( $settings, 'desktop' ) . fpwai_padding_css( $settings, 'desktop' ) . '}';
	$css .= '@media(max-width:' . $settings['breakpoint'] . 'px){#fpwai-button{' . fpwai_position_css( $settings, 'mobile' ) . fpwai_padding_css( $settings, 'mobile' ) . '}}';
	wp_add_inline_style( 'fpwai', $css );
	wp_localize_script(
		'fpwai',
		'fpwaiConfig',
		array(
			'manageManifest' => (bool) fpwai_manages_pwa(),
			'manifestUrl'    => fpwai_endpoint( 'manifest' ),
			'workerUrl'      => add_query_arg( 'fpwai_worker', '1', fpwai_endpoint( 'worker' ) ),
			'scope'          => fpwai_scope(),
			'triggerId'      => $settings['trigger_id'],
			'messages'       => array(
				'ios'         => __( 'To install this app, tap Share, then Add to Home Screen.', 'tehranwebseo-pwa-installer' ),
				'unavailable' => __( 'Installation is not available right now. If supported, use your browser menu to install this app or add it to your home screen.', 'tehranwebseo-pwa-installer' ),
				'close'       => __( 'Close', 'tehranwebseo-pwa-installer' ),
				'title'       => __( 'Install app', 'tehranwebseo-pwa-installer' ),
			),
		)
	);
}

function fpwai_padding_css( $settings, $device ) {
	return 'padding:' . $settings[ $device . '_padding_y' ] . 'px ' . $settings[ $device . '_padding_x' ] . 'px;';
}

function fpwai_position_css( $settings, $device ) {
	$corner = explode( '-', $settings[ $device . '_corner' ] );
	$block  = 'top' === $corner[0] ? 'start' : 'end';
	return 'inset:auto;inset-block-' . $block . ':calc(' . $settings[ $device . '_y' ] . 'px + env(safe-area-inset-' . $corner[0] . ',0px));inset-inline-' . $corner[1] . ':' . $settings[ $device . '_x' ] . 'px;';
}

function fpwai_render_icon( $icon ) {
	$paths = array(
		'download'        => 'M12 3v12m-5-5 5 5 5-5M5 17v4h14v-4',
		'plus'            => 'M12 5v14M5 12h14',
		'smartphone'      => 'M7 2h10a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1ZM10 5h4M11 19h2',
		'monitor'         => 'M3 3h18a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1ZM12 17v4M8 21h8',
		'download-circle' => 'M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM12 7v10M8 13l4 4 4-4',
	);
	$path = $paths[ $icon ] ?? $paths['download'];
	echo '<svg aria-hidden="true" focusable="false" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="' . esc_attr( $path ) . '"/></svg>';
}

add_action( 'wp_footer', 'fpwai_render_button' );
function fpwai_render_button() {
	$settings = fpwai_settings();
	if ( ! $settings['enabled'] || ! $settings['floating'] || ! wp_script_is( 'fpwai', 'enqueued' ) ) {
		return;
	}
	echo '<button id="fpwai-button" type="button" hidden>';
	if ( $settings['show_icon'] ) {
		fpwai_render_icon( $settings['icon'] );
	}
	echo '<span>' . esc_html( $settings['button_text'] ) . '</span></button>';
}
