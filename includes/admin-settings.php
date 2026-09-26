<?php
defined( 'ABSPATH' ) || exit;

function fpwai_fields() {
	$corners = array(
		'bottom-end'   => __( 'Bottom end', 'tehranwebseo-pwa-installer' ),
		'bottom-start' => __( 'Bottom start', 'tehranwebseo-pwa-installer' ),
		'top-end'      => __( 'Top end', 'tehranwebseo-pwa-installer' ),
		'top-start'    => __( 'Top start', 'tehranwebseo-pwa-installer' ),
	);
	return array(
		'enabled'             => array( 'checkbox', __( 'Enable installer', 'tehranwebseo-pwa-installer' ), true ),
		'manage_manifest'     => array( 'checkbox', __( 'Provide manifest and service worker', 'tehranwebseo-pwa-installer' ), true ),
		'floating'            => array( 'checkbox', __( 'Show floating button', 'tehranwebseo-pwa-installer' ), true ),
		'desktop_corner'      => array( 'select', __( 'Desktop corner', 'tehranwebseo-pwa-installer' ), 'bottom-end', $corners ),
		'desktop_x'           => array( 'number', __( 'Desktop horizontal offset (px)', 'tehranwebseo-pwa-installer' ), 24, array( 0, 1000 ) ),
		'desktop_y'           => array( 'number', __( 'Desktop vertical offset (px)', 'tehranwebseo-pwa-installer' ), 24, array( 0, 1000 ) ),
		'desktop_padding_x'   => array( 'number', __( 'Desktop horizontal padding (px)', 'tehranwebseo-pwa-installer' ), 20, array( 0, 200 ) ),
		'desktop_padding_y'   => array( 'number', __( 'Desktop vertical padding (px)', 'tehranwebseo-pwa-installer' ), 12, array( 0, 200 ) ),
		'mobile_corner'       => array( 'select', __( 'Mobile corner', 'tehranwebseo-pwa-installer' ), 'bottom-end', $corners ),
		'mobile_x'            => array( 'number', __( 'Mobile horizontal offset (px)', 'tehranwebseo-pwa-installer' ), 16, array( 0, 1000 ) ),
		'mobile_y'            => array( 'number', __( 'Mobile vertical offset (px)', 'tehranwebseo-pwa-installer' ), 16, array( 0, 1000 ) ),
		'mobile_padding_x'    => array( 'number', __( 'Mobile horizontal padding (px)', 'tehranwebseo-pwa-installer' ), 20, array( 0, 200 ) ),
		'mobile_padding_y'    => array( 'number', __( 'Mobile vertical padding (px)', 'tehranwebseo-pwa-installer' ), 12, array( 0, 200 ) ),
		'breakpoint'          => array( 'number', __( 'Mobile breakpoint (px)', 'tehranwebseo-pwa-installer' ), 768, array( 320, 2560 ) ),
		'background'          => array( 'color', __( 'Button background', 'tehranwebseo-pwa-installer' ), '#2563eb' ),
		'foreground'          => array( 'color', __( 'Button text color', 'tehranwebseo-pwa-installer' ), '#ffffff' ),
		'hover_background'    => array( 'color', __( 'Hover background', 'tehranwebseo-pwa-installer' ), '#1d4ed8' ),
		'hover_foreground'    => array( 'color', __( 'Hover text color', 'tehranwebseo-pwa-installer' ), '#ffffff' ),
		'radius'              => array( 'number', __( 'Border radius (px)', 'tehranwebseo-pwa-installer' ), 28, array( 0, 200 ) ),
		'button_text'         => array( 'text', __( 'Button text', 'tehranwebseo-pwa-installer' ), __( 'نصب برنامه', 'tehranwebseo-pwa-installer' ) ),
		'show_icon'           => array( 'checkbox', __( 'Show button icon', 'tehranwebseo-pwa-installer' ), true ),
		'icon'                => array( 'select', __( 'Button icon', 'tehranwebseo-pwa-installer' ), 'download', array( 'download' => __( 'Download', 'tehranwebseo-pwa-installer' ), 'plus' => __( 'Plus', 'tehranwebseo-pwa-installer' ), 'smartphone' => __( 'Smartphone', 'tehranwebseo-pwa-installer' ), 'monitor' => __( 'Monitor', 'tehranwebseo-pwa-installer' ), 'download-circle' => __( 'Circled download arrow', 'tehranwebseo-pwa-installer' ) ) ),
		'trigger_id'          => array( 'text', __( 'Custom trigger ID (without #)', 'tehranwebseo-pwa-installer' ), 'pwa-install-trigger' ),
		'theme_color'         => array( 'color', __( 'Manifest theme color', 'tehranwebseo-pwa-installer' ), '#2563eb' ),
		'background_color'    => array( 'color', __( 'Manifest background color', 'tehranwebseo-pwa-installer' ), '#ffffff' ),
		'delete_on_uninstall' => array( 'checkbox', __( 'Delete settings on uninstall', 'tehranwebseo-pwa-installer' ), false ),
	);
}

function fpwai_normalize_settings( $input, $submission = false ) {
	$input  = is_array( $input ) ? $input : array();
	$output = array();
	foreach ( fpwai_fields() as $key => $field ) {
		$value = $input[ $key ] ?? ( $submission && 'checkbox' === $field[0] ? false : $field[2] );
		if ( ! is_scalar( $value ) ) {
			$value = $field[2];
		}
		switch ( $field[0] ) {
			case 'checkbox':
				$value = in_array( $value, array( true, 1, '1' ), true );
				break;
			case 'number':
				$value = is_numeric( $value ) ? max( $field[3][0], min( $field[3][1], (int) $value ) ) : $field[2];
				break;
			case 'color':
				$value = sanitize_hex_color( (string) $value ) ?: $field[2];
				break;
			case 'select':
				$value = isset( $field[3][ $value ] ) ? $value : $field[2];
				break;
			default:
				$value = sanitize_text_field( (string) $value );
				if ( 'trigger_id' === $key ) {
					$value = preg_replace( '/[\s#<>"\x27]/u', '', $value );
				} elseif ( '' === $value ) {
					$value = $field[2];
				}
		}
		$output[ $key ] = $value;
	}
	return $output;
}

add_action( 'admin_init', 'fpwai_register_settings' );
function fpwai_register_settings() {
	register_setting( 'fpwai', FPWAI_OPTION, array( 'type' => 'array', 'sanitize_callback' => 'fpwai_save_settings', 'show_in_rest' => false ) );
	add_settings_section( 'fpwai_main', '', '__return_false', 'fpwai' );
	foreach ( fpwai_fields() as $key => $field ) {
		add_settings_field( $key, $field[1], 'fpwai_render_field', 'fpwai', 'fpwai_main', array( 'key' => $key, 'field' => $field, 'label_for' => 'fpwai-' . $key ) );
	}
}

function fpwai_save_settings( $input ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return get_option( FPWAI_OPTION, array() );
	}
	// options.php verifies the Settings API nonce before invoking this callback.
	return fpwai_normalize_settings( $input, true );
}

function fpwai_render_field( $args ) {
	$settings = fpwai_settings();
	$key      = $args['key'];
	$field    = $args['field'];
	$value    = $settings[ $key ];
	$name     = FPWAI_OPTION . '[' . $key . ']';
	$id       = 'fpwai-' . $key;
	if ( 'icon' === $key ) {
		echo '<fieldset class="fpwai-icon-picker"><legend class="screen-reader-text">' . esc_html( $field[1] ) . '</legend>';
		foreach ( $field[3] as $choice => $label ) {
			$choice_id = 'download' === $choice ? $id : $id . '-' . $choice;
			echo '<label class="fpwai-icon-choice" title="' . esc_attr( $label ) . '">';
			echo '<input type="radio" id="' . esc_attr( $choice_id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $choice ) . '" ' . checked( $value, $choice, false ) . '>';
			echo '<span class="fpwai-icon-preview">';
			fpwai_render_icon( $choice );
			echo '</span><span class="screen-reader-text">' . esc_html( $label ) . '</span></label>';
		}
		echo '</fieldset>';
		return;
	}
	if ( 'select' === $field[0] ) {
		echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '">';
		foreach ( $field[3] as $choice => $label ) {
			echo '<option value="' . esc_attr( $choice ) . '" ' . selected( $value, $choice, false ) . '>' . esc_html( $label ) . '</option>';
		}
		echo '</select>';
		return;
	}
	if ( 'checkbox' === $field[0] ) {
		echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="0">';
		echo '<input type="checkbox" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="1" ' . checked( $value, true, false ) . '>';
		return;
	}
	echo '<input type="' . esc_attr( $field[0] ) . '" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '"';
	if ( 'number' === $field[0] ) {
		echo ' min="' . esc_attr( $field[3][0] ) . '" max="' . esc_attr( $field[3][1] ) . '" step="1"';
	}
	if ( in_array( $field[0], array( 'color', 'number' ), true ) || 'trigger_id' === $key ) {
		echo ' dir="ltr"';
	}
	echo '>';
}

add_action( 'admin_enqueue_scripts', 'fpwai_admin_assets' );
function fpwai_admin_assets( $hook_suffix ) {
	if ( 'toplevel_page_fpwai' !== $hook_suffix || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	wp_enqueue_style( 'fpwai-admin', FPWAI_URL . 'assets/css/admin.css', array(), FPWAI_VERSION );
}

add_action( 'admin_menu', 'fpwai_admin_menu' );
function fpwai_admin_menu() {
	add_menu_page( __( 'Floating PWA Installer', 'tehranwebseo-pwa-installer' ), __( 'Floating PWA Installer', 'tehranwebseo-pwa-installer' ), 'manage_options', 'fpwai', 'fpwai_settings_page', 'dashicons-download', 65.1 );
}

function fpwai_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$brand_url  = 'https://tehranwebseo.ir/';
	$brand_logo = FPWAI_URL . 'assets/img/tehran-web-seo-mini-logo.webp';

	echo '<div class="wrap">';
	echo '<h1>' . esc_html__( 'Floating PWA Installer', 'tehranwebseo-pwa-installer' ) . '</h1>';

	// Brand / promotional notice (top of page).
	echo '<div class="fpwai-brand-box">';
	echo '<a class="fpwai-brand-logo-link" href="' . esc_url( $brand_url ) . '" target="_blank" rel="noopener noreferrer">';
	echo '<img class="fpwai-brand-logo" src="' . esc_url( $brand_logo ) . '" alt="' . esc_attr__( 'Tehran Web SEO', 'tehranwebseo-pwa-installer' ) . '" loading="lazy" decoding="async">';
	echo '</a>';

	echo '<div class="fpwai-brand-content">';
	echo '<p class="fpwai-brand-title">' . esc_html__( 'Created by Tehran Web SEO', 'tehranwebseo-pwa-installer' ) . '</p>';
	echo '<p class="fpwai-brand-desc">';
	esc_html_e( 'Need professional WordPress, WooCommerce, and technical SEO solutions?', 'tehranwebseo-pwa-installer' );
	echo ' ';
	echo '<a href="' . esc_url( $brand_url ) . '" target="_blank" rel="noopener noreferrer">';
	esc_html_e( 'Visit TehranWebSEO.ir', 'tehranwebseo-pwa-installer' );
	echo '</a>';
	echo '</p>';
	echo '</div>';
	echo '</div>';

	echo '<p><strong>' . esc_html__( 'Important notes:', 'tehranwebseo-pwa-installer' ) . '</strong></p>';
	echo '<p>';
	esc_html_e( 'The button is not shown to users who have already installed the app.', 'tehranwebseo-pwa-installer' );
	echo '</p><p>';
	esc_html_e( 'Disable manifest and service worker management when another PWA solution is active.', 'tehranwebseo-pwa-installer' );
	echo '</p>';

	echo '<form action="options.php" method="post">';
	settings_fields( 'fpwai' );
	do_settings_sections( 'fpwai' );
	submit_button();
	echo '</form></div>';
}

