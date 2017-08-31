<?php

// add_action( 'wp_footer', function() {
// 	echo date( 'D' );
// });

wp_schedule_event( time() + 900, 'daily', 'fittin_weekly_email' );

add_action( 'fittin_weekly_email', function() {

	// check it's sunday
	// if ( 'Sun' !== date( 'D' ) ) {
	// 	return;
	// }

	$args = array(
		 'fields' => 'all',
		//  'fields' => 'id',
		 'no_found_rows' => true,
		//  'role__in' => array( 'Group Leader', 'Subscriber' )
	);
	$user_query = new WP_User_Query( $args );

	// User Loop
	$x = 0; // counter
	if ( ! empty( $user_query->results ) ) {
		foreach ( $user_query->results as $user ) {
			// print_r($user->data->user_email);
			$send_email = false;

			$log = get_user_meta( $user->ID, 'time_list', true);

			// get start/end day  (run this job on a sunday)
			$first_day = date('d-m-Y',strtotime('last monday -7 days')); // mon
			$last_day = date('d-m-Y',strtotime('last monday -2 days')); // sat


// test value overrides
$first_day = date( 'U', strtotime( 'last monday -9999 days' ) );
$last_day = date('U');

			$y = 0; // counter

			$output = "<img width='80' src='https://www.fitt-in.co.uk/wp-content/uploads/2017/03/logo.png' alt='Fitt-In' style='margin-bottom: 20px'><p>Please find your video views for this week below: </p>";
			if ( !empty( $log ) ) {
				$output .= "<table style='margin-bottom:20px; border-collapse: collapse' cellspacing='0' cellpadding='0'><tr><td style='font-weight: bold'>Date</td><td style='font-weight: bold'>Video views (mins)</td></tr>";
				$total_time = 0;
				foreach( $log as $key => $value ) {
					$uni_key = strtotime($key);

					// if falls within given week
					if ( date( 'U', $uni_key ) > $first_day && date( 'U', $uni_key ) < $last_day ) {
						$send_email = true;
						$time = 0;
						foreach ( $value as $entry ) {
							$time += $entry['video_duration'];
							$total_time += $entry['video_duration'];
						}

						$output .= "<tr><td style='border:1px solid #333; padding: 2px 4px;'>". date( 'D jS F, Y', $uni_key ) . "</td><td style='border:1px solid #333; padding: 2px 4px;'>" . round( $time / 60 ) . " mins</td></tr>";

					}
					$y++;
				}
				$output .= "<tr><td style='border:1px solid #333; padding: 2px 4px; font-weight:bold'>TOTAL</td><td style='border:1px solid #333; padding: 2px 4px; font-weight:bold'>" . round( $total_time / 60 ) . " mins</td></tr></table><p>Kind regards,<br>Fitt-in</p>";

			}

			// echo  $output;
			// get Log

			$headers = 'From: Fitt-In <no-reply@fitt-in.co.uk>' . "\r\n";
			if ( true === $send_email ) {
				// wp_mail( $user->data->user_email, 'Your video views this week', $output, $headers );
				wp_mail( 'cpd@loopmill.com', "Your video views this week(email: " . $user->data->user_email . ")", $output, $headers );
			}

			$x++;
		} // foreach user
	}
});

add_filter( 'wp_mail_content_type', function() {
	return "text/html";
});
