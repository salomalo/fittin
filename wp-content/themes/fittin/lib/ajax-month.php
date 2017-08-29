<?php

// ============
// AJAX Handler
// ============

function month_view_button() {

	$time_log = get_user_meta( get_current_user_id(), 'time_list', true );

	if ( !empty ( $_POST['selected_month'] ) ) {
		$datesminutes = json_encode( view_month( $time_log, esc_html( $_POST['selected_month'] ) ) );
	} else {
		$datesminutes = json_encode( view_month( $time_log ) );
	}

	wp_die($datesminutes);

}
add_action( 'wp_ajax_month_view_button', 'month_view_button' );
