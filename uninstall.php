<?php
defined( 'ABSPATH' ) || exit;
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

function fpwai_delete_site_settings() {
	$settings = get_option( 'fpwai_settings', array() );
	if ( is_array( $settings ) && ! empty( $settings['delete_on_uninstall'] ) ) {
		delete_option( 'fpwai_settings' );
	}
}

if ( is_multisite() ) {
	$fpwai_offset = 0;
	do {
		$fpwai_site_ids = get_sites( array( 'fields' => 'ids', 'number' => 100, 'offset' => $fpwai_offset ) );
		foreach ( $fpwai_site_ids as $fpwai_site_id ) {
			switch_to_blog( $fpwai_site_id );
			fpwai_delete_site_settings();
			restore_current_blog();
		}
		$fpwai_offset += 100;
	} while ( count( $fpwai_site_ids ) === 100 );
} else {
	fpwai_delete_site_settings();
}
