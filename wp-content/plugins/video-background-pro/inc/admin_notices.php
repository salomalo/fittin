<?php
/**
 * Exit if accessed directly
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Create admin notices
 *
 * @since 1.0.0
 *
 * @uses __()
 * @uses get_option()
 */
function vidbgpro_admin_notices() {
	$class = 'notice notice-success vidbgpro-admin-notice is-dismissible';
	$message = __( 'Thank you for purchasing Video Background Pro! If you need any assistance, please visit the <a href="http://pushlabs.co/documentation/video-background-pro" target="_blank">documentation</a>.', 'video-background-pro' );
	$is_dismissed = get_option( 'vidbgpro-admin-notice-dismissed' );
	if( empty( $is_dismissed ) ) {
		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
	}
}
add_action( 'admin_notices', 'vidbgpro_admin_notices' );

/**
 * Ajax handler to permanently dismiss notice
 *
 * @since 1.0.0
 *
 * @uses update_option()
 */
function vidbgpro_dismiss_notices() {
	update_option( 'vidbgpro-admin-notice-dismissed', 1 );
}
add_action( 'wp_ajax_vidbgpro_dismiss_notices', 'vidbgpro_dismiss_notices' );
