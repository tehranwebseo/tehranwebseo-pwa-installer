<?php
/**
 * Plugin Name: Floating PWA Installer
 * Description: Accessible floating PWA installation with an optional manifest and service worker.
 * Version: 1.0.1
 * Author: Tehran Web SEO
 * Author URI: https://tehranwebseo.ir/
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: tehranwebseo-pwa-installer
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

define( 'FPWAI_VERSION', '1.0.1' );
define( 'FPWAI_DIR', plugin_dir_path( __FILE__ ) );
define( 'FPWAI_URL', plugin_dir_url( __FILE__ ) );
define( 'FPWAI_OPTION', 'fpwai_settings' );

require_once FPWAI_DIR . 'includes/admin-settings.php';
require_once FPWAI_DIR . 'includes/manifest-generator.php';
require_once FPWAI_DIR . 'includes/service-worker.php';
require_once FPWAI_DIR . 'includes/frontend.php';

function fpwai_settings() {
	return fpwai_normalize_settings( get_option( FPWAI_OPTION, array() ) );
}

function fpwai_manages_pwa() {
	$settings = fpwai_settings();
	// Disable this option/filter for another PWA provider; JS also preserves existing manifest links and workers.
	return $settings['enabled'] && $settings['manage_manifest'] && apply_filters( 'fpwai_manage_manifest', true );
}

function fpwai_scope() {
	return trailingslashit( wp_parse_url( home_url( '/' ), PHP_URL_PATH ) ?: '/' );
}

function fpwai_endpoint( $resource ) {
	$paths = array( 'manifest' => 'manifest.webmanifest', 'worker' => 'sw.js', 'icon' => 'fpwai-icon.svg' );
	if ( ! isset( $paths[ $resource ] ) ) {
		return '';
	}
	if ( get_option( 'permalink_structure' ) ) {
		return home_url( '/' . $paths[ $resource ] );
	}
	return add_query_arg( 'fpwai_resource', $resource, home_url( '/index.php' ) );
}

add_filter( 'query_vars', 'fpwai_query_vars' );
function fpwai_query_vars( $vars ) {
	$vars[] = 'fpwai_resource';
	return $vars;
}

add_action( 'parse_request', 'fpwai_serve_resource', 0 );
function fpwai_serve_resource( $request ) {
	$paths    = array( 'manifest.webmanifest' => 'manifest', 'sw.js' => 'worker', 'fpwai-icon.svg' => 'icon' );
	$resource = $paths[ trim( $request->request, '/' ) ] ?? ( $request->query_vars['fpwai_resource'] ?? '' );
	if ( ! is_string( $resource ) || ! in_array( $resource, array( 'manifest', 'worker', 'icon' ), true ) ) {
		return;
	}
	if ( ! fpwai_manages_pwa() ) {
		status_header( 404 );
		nocache_headers();
		exit;
	}
	$method = isset( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : 'GET';
	if ( ! in_array( $method, array( 'GET', 'HEAD' ), true ) ) {
		status_header( 405 );
		header( 'Allow: GET, HEAD' );
		exit;
	}
	status_header( 200 );
	nocache_headers();
	header( 'X-Content-Type-Options: nosniff' );
	if ( 'worker' === $resource ) {
		fpwai_serve_worker( $method );
	} elseif ( 'icon' === $resource ) {
		header( 'Content-Type: image/svg+xml; charset=UTF-8' );
		if ( 'HEAD' !== $method ) {
			echo '<svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512"><rect width="512" height="512" rx="96" fill="#2563eb"/><path d="M256 112v208m-80-80 80 80 80-80M144 344v56h224v-56" fill="none" stroke="#fff" stroke-width="32" stroke-linecap="round" stroke-linejoin="round"/></svg>';
		}
	} else {
		header( 'Content-Type: application/manifest+json; charset=UTF-8' );
		if ( 'HEAD' !== $method ) {
			echo wp_json_encode( fpwai_manifest(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		}
	}
	exit;
}
