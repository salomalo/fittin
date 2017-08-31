<?php

// add_action( 'wp_footer', function() {
// 	echo date( 'D' );
// });

wp_schedule_event( time(), 'daily', 'fittin_weekly_email' );

add_action( 'wp_footer', function() {
// add_action( 'fittin_weekly_email', function() {

	// check it's sunday
	// if ( 'Sun' !== date( 'D' ) ) {
	// 	return;
	// }

	$args = array(
		 'fields' => 'all',
		//  'fields' => 'id',
		 'no_found_rows' => true,
		 'role__in' => array( 'Group Leader', 'Subscriber' )
	);
	$user_query = new WP_User_Query( $args );

	// User Loop
	$x = 0; // counter
	if ( ! empty( $user_query->results ) ) {
		foreach ( $user_query->results as $user ) {

			$log = get_user_meta( $user->ID, 'time_list', true);

			// get start/end day  (run this job on a sunday)
			$first_day = date( 'd-m-Y', strtotime( 'last monday -7 days' )); // mon
			$last_day = date( 'd-m-Y', strtotime( 'last monday -2 days' )); // sat


// test value overrides
$first_day = date( 'U', strtotime( 'last monday -9999 days' ) );
$last_day = date('U');

			$output = "<img width='80' src='https://www.fitt-in.co.uk/wp-content/uploads/2017/03/logo.png' alt='Fitt-In' style='margin-bottom: 20px'><p>Hello " . $user->data->display_name . " (" . $user->roles[0] . "),</p>";
			if ( 'Group Leader' == $user->roles[0] ) {

				// get group leader's group id
				global $wpdb;
				$sql = "SELECT id, group_name FROM " . $wpdb -> prefix . "group_sets WHERE group_leader = '" . $user->ID . "'";
				$result	= $wpdb -> get_row($sql);
				if ( count( $result ) > 0 ) {

					// now get users from group
					$gMemSql = "SELECT * FROM " . $wpdb -> prefix . "group_sets_members WHERE group_id = '" . $result->id . "' ORDER BY createdDate";
					$gMemResults = $wpdb -> get_results($gMemSql);
					foreach( $gMemResults as $member ) {

						// $output .= '<pre>'.print_r( $member, true ).'</pre>';
						$member_details = get_user_by( 'ID', $member->member_id );
						$member_log = get_user_meta( $member->member_id, 'time_list', true );
						// $output .= '<pre>'.print_r( $member_log, true ).'</pre>';
						$output .= add_log_times( $member_log, $first_day, $last_day, $member_details->data->display_name );

					}
				} else {
					$output .= 'NO SUB USERS!?';
				}// if results

			} // if group leader role

			$output .= add_log_times( $log, $first_day, $last_day, $user->data->display_name );

			$output .= '<p>Kind regards,<br>Fitt-in</p>';
			// echo  $output;
			// get Log

			$headers = 'From: Fitt-In <no-reply@fitt-in.co.uk>' . "\r\n";


			// if ( true === $send_email ) {
				// echo '<pre>' . print_r($user->roles,true) . '</pre>';

				// wp_mail( $user->data->user_email, 'Your video views this week', $output, $headers );
				// wp_mail( 'cpd@loopmill.com', "Your video views this week(email: " . $user->data->user_email . ")", $output, $headers );
				echo $output;
			// }

			$x++;
		} // foreach user
	}
});

add_filter( 'wp_mail_content_type', function() {
	return "text/html";
});







function add_log_times( $log, $first_day, $last_day, $name ) {
	if ( !empty( $log ) ) {
		$y = 0; // counter
		$output = $name . "<table style='margin-bottom:20px; border-collapse: collapse' cellspacing='0' cellpadding='0'><tr><td style='font-weight: bold'>Date</td><td style='font-weight: bold'>". $name . "&#39;s Video views (mins)</td></tr>";
		$total_time = 0;
		foreach( $log as $key => $value ) {
			$uni_key = strtotime($key);

			// if falls within given week
			if ( date( 'U', $uni_key ) > $first_day && date( 'U', $uni_key ) < $last_day ) {
				$send_email = true; // @todo always send email, say 0 mins!

				$time = 0;
				foreach ( $value as $entry ) {
					$time += $entry['video_duration'];
					$total_time += $entry['video_duration'];
				}

				$output .= "<tr><td style='border:1px solid #333; padding: 2px 4px;'>". date( 'D jS F, Y', $uni_key ) . "</td><td style='border:1px solid #333; padding: 2px 4px;'>" . round( $time / 60 ) . " mins</td></tr>";

			}
			$y++;
		}
		$output .= "<tr><td style='border:1px solid #333; padding: 2px 4px; font-weight:bold'>TOTAL</td><td style='border:1px solid #333; padding: 2px 4px; font-weight:bold'>" . round( $total_time / 60 ) . " mins</td></tr></table>";

	} else {
		$output = '<p>' . $name . ' - No video views this week.</p>';
	}
	return $output;

}
