<?php

// ============
// AJAX Handler
// ============

function ajax_handler() {

	global $wpdb; // this is how you get access to the database

	$user = intval( $_POST['user'] );
	$video_duration = intval( $_POST['video_length'] );
	$video_id = intval( $_POST['video_id'] );

	$recent = get_user_meta( $user, 'time_list_most_recent' );

	if ( '' != $recent[0] ) {
		// compare current time to recent time
		$time_difference =  time() - $recent[0];

		if ( $time_difference > 1 ) { // 5 mins
			update_user_meta( $user, 'time_list_most_recent', array(
					$video_id  => time()
				)
			);
		} else {
			die('not enough time elapsed');
		}

	} else { // no recent time found
		update_user_meta( $user, 'time_list_most_recent', array(
			'video_id' => $video_id,
			'timestamp' => time()
			)
		);
	}

    $time_list = get_user_meta( $user, 'time_list'  );

    if ( '' == $time_list ) {
        $time_list = array();
    }

    array_push( $time_list, array( 'video_id' => $video_id, 'timestamp' => time(), 'video_duration' => $video_duration ) );

    update_user_meta( $user, 'time_list', $time_list );

    wp_die($recent);

}
add_action( 'wp_ajax_my_action', 'ajax_handler' );
