<?php

function view_default( $time_log ) {

	$minutes = [];
	$dates = [];

	if ( $time_log && isset( $time_log ) ) {

		// $sort_stamp = strtotime($time_log[0][0]);
		// ksort( date( 'U', $sort_stamp ) ); // sorts by key
		// ksort( $time_log[0] );
		// uksort( $time_log[0], 'datediff' );


		foreach ( $time_log as $day_key => $day_log ) {

			array_push( $dates, $day_key );

			$second_calculation = 0;

			if ( $day_log && isset( $day_log ) ) {

				foreach ( $day_log as $day_log_timestamp ) {
					$second_calculation += $day_log_timestamp['video_duration'];
				}

			}

			array_push( $minutes, intval($second_calculation/60) );

		} // foreach

	} // if

	return array( 'dates' => $dates, 'minutes' => $minutes );

}
