<?php
defined( 'ABSPATH' ) || exit;

function fpwai_manifest_icons() {
	$icons = array();
	foreach ( array_unique( array( (int) get_option( 'site_icon' ), (int) get_theme_mod( 'custom_logo' ) ) ) as $attachment ) {
		if ( ! $attachment ) {
			continue;
		}
		foreach ( array( array( 192, 192 ), array( 512, 512 ), 'full' ) as $size ) {
			$image = wp_get_attachment_image_src( $attachment, $size );
			if ( $image && $image[1] === $image[2] && $image[1] >= 192 ) {
				$icons[ $image[1] ] = array(
					'src'     => esc_url_raw( $image[0] ),
					'sizes'   => $image[1] . 'x' . $image[2],
					'purpose' => 'any',
				);
			}
		}
		if ( $icons ) {
			break;
		}
	}
	if ( ! $icons ) {
		$icons[] = array(
			'src'     => fpwai_endpoint( 'icon' ),
			'sizes'   => 'any',
			'type'    => 'image/svg+xml',
			'purpose' => 'any',
		);
	}
	return array_values( $icons );
}

function fpwai_manifest() {
	$settings = fpwai_settings();
	$name     = html_entity_decode( wp_strip_all_tags( get_bloginfo( 'name' ) ), ENT_QUOTES, 'UTF-8' );
	return array(
		'id'               => fpwai_scope(),
		'name'             => $name,
		'short_name'       => $name,
		'lang'             => get_bloginfo( 'language' ),
		'dir'              => is_rtl() ? 'rtl' : 'ltr',
		'start_url'        => home_url( '/' ),
		'scope'            => fpwai_scope(),
		'display'          => 'standalone',
		'theme_color'      => $settings['theme_color'],
		'background_color' => $settings['background_color'],
		'icons'            => fpwai_manifest_icons(),
	);
}
