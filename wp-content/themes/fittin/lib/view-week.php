<?php

function view_week( $time_log ) {

	$minutes = [];
	$dates = [];
	$current_day_midnight = 999999999999; // initially allow first value

	if ( $time_log && isset( $time_log ) ) {

		ksort( $time_log[0] ); // sorts by key (day in nice format)
		// $time_log[0] = array_reverse( $time_log[0] );
		$x = 0;
		foreach ( $time_log[0] as $day_key => $day_log ) {

			if ( 0 == $x ) {
				$first_day = $day_log[0]['time'];
				$first_day_midnight = mktime( 0, 0, 0, date( "n", $first_day ), date( "j", $first_day ) - date( "N", $first_day ) + 1 );
				$last_day = $first_day_midnight + 7 * DAY_IN_SECONDS;
			}

			// if ( $day_log[0]['time'] < $last_day ) { // check for week against first time entry

				if ( $day_log[0]['time'] < $current_day_midnight + ( 1 * DAY_IN_SECONDS ) ) { // check if days skipped

					array_push( $dates, $day_key );
					$second_calculation = 0;

					if ( $day_log && isset( $day_log ) ) { // get all entries for this day

						foreach ( $day_log as $day_log_timestamp ) {
							$second_calculation += $day_log_timestamp['video_duration'];
						}

					}

					array_push( $minutes, intval($second_calculation/60) );

					$current_day = $day_log[0]['time'];
					$current_day_midnight = $current_day - $current_day%86400;

				} else {

					$no_days_skipped = ceil( ( $day_log[0]['time'] - $current_day_midnight ) / DAY_IN_SECONDS );

					for ($x = 0; $x < $no_days_skipped; $x++) {
						array_push( $dates, 'Day skipped' );
						array_push( $minutes, 0 );
						$current_day_midnight += 1 * DAY_IN_SECONDS;
					}

				}

			// } else { // more than 7 days
				// array_push( $dates, 'TOO FAR AWAY' );
			// }

			$x++;
		} // foreach

	} // if

	return array( $dates, $minutes, $first_day, $first_day_midnight, $last_day, 'days_skipped' => $current_day_midnight );

}
