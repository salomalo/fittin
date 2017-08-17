<?php

add_action( 'wp_footer', function() {
	echo 'nabafbsdfbgbdgfbdgbfgb';
	$args = array(
		 'fields' => 'all',
		//  'fields' => 'id',
		 'no_found_rows' => true,
		//  'role__in' => array( 'Group Leader', 'Subscriber' )
	);
	$user_query = new WP_User_Query( $args );

	// User Loop
	$x = 0;
	if ( ! empty( $user_query->results ) ) {
		foreach ( $user_query->results as $user ) {
			// print_r($user->data->user_email);
			$send_email = false;
			echo "<h1 style='color:#000'>$x</h1>";

			echo "<pre>";

			$log = get_user_meta( $user->ID, 'time_list', true);

			// get start/end day  (run this job on a sunday)
			$first_day = date('d-m-Y',strtotime('last monday -7 days')); // mon
			$last_day = date('d-m-Y',strtotime('last monday -2 days')); // sat


// test values
$first_day = date( 'U', strtotime( 'last monday -9999 days' ) );
$last_day = date('U');
			$y = 0;

			$output = "<p>Please find your video views for this week below: </p> <table>";
			foreach( $log as $key => $value ) {
				$uni_key = strtotime($key);

				// if falls within given week
				if ( date( 'U', $uni_key ) > $first_day && date( 'U', $uni_key ) < $last_day ) {
					$send_email = true;
					$time = 0;
					foreach ( $value as $entry ) {
						$time += $entry['video_duration'];
					}

					$output .= "<tr><td>". date( 'D jS F, Y', $uni_key ) . "</td><td>" . round( $time / 60 ) . " mins</td></tr>";

				}
				$y++;
			}
			$output .= "</table><p>Kind regards,<br>Fitt-in</p>";

			echo "</pre>";

			echo  $output;
			// get Log

			$headers = 'From: Fitt-In <no-reply@fitt-in.co.uk>' . "\r\n";
			if ( true == $send_email ) {
				// wp_mail( $user->data->user_email, 'Your video views this week', $output, $headers );
				wp_mail( 'ch@loopmill.com', "Your video views this week(email: " . $user->data->user_email . ")", $output, $headers );
			}

			$x++;
		} // foreach user
	}
});

add_filter( 'wp_mail_content_type', function() {
	return "text/html";
});
