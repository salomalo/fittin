<?php

// ============
// AJAX Handler
// ============

function week_view_button() {

	if ( empty( $_POST['subuser'] ) ) {
		$user_info = get_userdata( get_current_user_id() );
		$sub_users = get_sub_users( $user_info );
		$time_log = $sub_users['time_log'];
		if ( false === $sub_users )  { // if no sub users
			$time_log = get_user_meta( get_current_user_id(), 'time_list', true );
		}
	} else {
		$time_log = get_user_meta( esc_html( $_POST['subuser'] ), 'time_list', true );
	}

	if ( $_POST['selected_week'] ) {
		$datesminutes = json_encode( view_week( $time_log, esc_html( $_POST['selected_week'] ) ) );
	} else {
		$datesminutes = json_encode( view_week( $time_log ) );
	}

	wp_die($datesminutes);

}
add_action( 'wp_ajax_week_view_button', 'week_view_button' );
