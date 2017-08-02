<?php

function view_month( $time_log ) {

	$minutes = [];
	$dates = [];
	$current_month = date('m');
	$current_year = date('Y');

	if ( $time_log && isset( $time_log ) ) {

		// $sort_stamp = strtotime($time_log[0][0]);
		// ksort( date( 'U', $sort_stamp ) ); // sorts by key
		// ksort( $time_log[0] );

		foreach ( $time_log[0] as $day_key => $day_log ) {

			$timestamp = strtotime($day_key);
			if ( date( 'm', $timestamp ) == $current_month &&  date( 'Y', $timestamp ) == $current_year ) {
				array_push( $dates, $day_key );

				$second_calculation = 0;

				if ( $day_log && isset( $day_log ) ) {

					foreach ( $day_log as $day_log_timestamp ) {
						$second_calculation += $day_log_timestamp['video_duration'];
					}

				}

				array_push( $minutes, intval($second_calculation/60) );
			}

		} // foreach

	} // if

	return array( 'dates' => $dates, 'minutes' => $minutes, 'month' => date('F'), 'year' => $current_year );

}
