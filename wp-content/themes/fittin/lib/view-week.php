<?php

function view_week( $time_log, $selected_week ) {

	$minutes = [];
	$dates = [];

	if ( $time_log && isset( $time_log ) ) {

		$x = 0;
		foreach ( $time_log[0] as $day_key => $day_log ) {

			if ( 0 == $x ) {

				// get current week
				$week_no = idate( 'W', time() );
				if ( !empty( $selected_week ) ) {
					$week_no = idate( 'W', $selected_week );
				}


				$week_commencing = date( "jS F Y", strtotime( date( 'Y', $day_log[0]['time'] ) . "W" . $week_no ) ); // 2011-01-03

			}

			if ( idate( 'W', $day_log[0]['time'] ) == $week_no ) { // check for week against first time entry
				$timestamp = strtotime( $day_key );

				array_push( $dates, date( 'D jS', $timestamp ) );
				$second_calculation = 0;

				if ( $day_log && isset( $day_log ) ) { // get all entries for this day
					foreach ( $day_log as $day_log_timestamp ) {
						$second_calculation += $day_log_timestamp['video_duration'];
					}
				}

				array_push( $minutes, intval($second_calculation/60) );


			} else { // more than 7 days
				// array_push( $dates, 'TOO FAR AWAY' );
			}

			$x++;
		} // foreach

	} // if

	return array( 'dates' => $dates, 'minutes' => $minutes, 'week_no' => $week_no, 'week_commencing' => $week_commencing );

}
