<?php
/**
 * Uninstall routines for Floating PWA Installer.
 *
 * @package TehranWebSEO\PWAInstaller
 */

defined( 'ABSPATH' ) || exit;
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

/**
 * Delete plugin settings for current site when enabled.
 *
 * @return void
 */
function fpwai_delete_site_settings() {
	$settings = get_option( 'fpwai_settings', array() );

	if ( is_array( $settings ) && ! empty( $settings['delete_on_uninstall'] ) ) {
		delete_option( 'fpwai_settings' );
	}
}

if ( is_multisite() ) {
	$fpwai_offset = 0;
	$fpwai_limit  = 100;

	do {
		$fpwai_site_ids = get_sites(
			array(
				'fields' => 'ids',
				'number' => $fpwai_limit,
				'offset' => $fpwai_offset,
			)
		);

		foreach ( $fpwai_site_ids as $fpwai_site_id ) {
			switch_to_blog( $fpwai_site_id );
			fpwai_delete_site_settings();
			restore_current_blog();
		}

		$fpwai_offset += $fpwai_limit;
		$fpwai_count   = count( $fpwai_site_ids );
	} while ( $fpwai_count === $fpwai_limit );
} else {
	fpwai_delete_site_settings();
}
