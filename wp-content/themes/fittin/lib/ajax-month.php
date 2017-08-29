<?php

// ============
// AJAX Handler
// ============

function month_view_button() {

	$user_info = get_userdata( get_current_user_id() );
	$time_log = get_sub_users( $user_info );
	if ( false === $time_log )  { // if no sub users
		$time_log = get_user_meta( get_current_user_id(), 'time_list', true );
	}
	
	if ( !empty ( $_POST['selected_month'] ) ) {
		$datesminutes = json_encode( view_month( $time_log, esc_html( $_POST['selected_month'] ) ) );
	} else {
		$datesminutes = json_encode( view_month( $time_log ) );
	}

	wp_die($datesminutes);

}
add_action( 'wp_ajax_month_view_button', 'month_view_button' );
