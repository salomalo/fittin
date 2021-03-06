<?php

// ============
// AJAX Handler
// ============

function record_stats() {

	global $wpdb; // get access to the database

	$user = intval( $_POST['user'] );
	$video_duration = intval( $_POST['video_length'] );
	$video_id = intval( $_POST['video_id'] );
	$recent = get_user_meta( $user, 'time_list_most_recent', true );
	$time_list = get_user_meta( $user, 'time_list', true );

	// =======================================
	// if there is a 'recent' entry, check how recent
	// =======================================

	if ( '' != $recent ) {

		// compare current time to recent time
		$time_difference =  time() - $recent['timestamp'];

		if ( $time_difference < 300 ) { // 300 = 5 mins
			die('not enough time elapsed');
		}

	}

	// ==========================
	// update recent time
	// ==========================

	update_user_meta( $user, 'time_list_most_recent', array(
		'video_id' => $video_id,
		'timestamp' => time()
		)
	);

	// ================
	// update time list
	// ================

	$day = date( 'd-m-Y', time() );

	if ( !empty( $time_list ) ) { 
		$time_list[$day][] = array(
			'time' => time(),
			'video_id' => $video_id,
			'video_duration' => $video_duration
		);
	} else {
		$time_list = array(
			$day => array(
				array (
					'time' => time(),
					'video_id' => $video_id,
					'video_duration' => $video_duration
				)
			)
		);
	}

	update_user_meta( $user, 'time_list',  $time_list );

	wp_die( print_r( $time_list, true ) );

	// wp_die('complete');

}
add_action( 'wp_ajax_record_stats', 'record_stats' );
