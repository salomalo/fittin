<?php

// ============
// AJAX Handler
// ============

function default_view_button() {

	$time_log = get_user_meta( get_current_user_id(), 'time_list', true );
	$datesminutes = json_encode( view_default( $time_log ) );

	wp_die($datesminutes);

}
add_action( 'wp_ajax_default_view_button', 'default_view_button' );
