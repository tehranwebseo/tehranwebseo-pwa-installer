<?php
defined( 'ABSPATH' ) || exit;

function fpwai_serve_worker( $method = 'GET' ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';

	$filesystem = new WP_Filesystem_Direct( null );
	$script     = $filesystem->get_contents( FPWAI_DIR . 'assets/js/sw-template.js' );
	if ( false === $script ) {
		status_header( 503 );
		return;
	}

	header( 'Content-Type: application/javascript; charset=UTF-8' );
	header( 'Service-Worker-Allowed: ' . fpwai_scope() );
	header( 'X-FPWAI-Worker: ' . FPWAI_VERSION );
	if ( 'HEAD' !== $method ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static bundled JavaScript must be served without HTML escaping.
		echo $script;
	}
}
