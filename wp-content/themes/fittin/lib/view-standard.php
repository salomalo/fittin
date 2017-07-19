<?php

function view_standard( $time_log ) {

	$minutes = '';
	$dates = '';

	if ( $time_log && isset( $time_log ) ) {

		ksort( $time_log[0] ); // sorts by key (day in nice format)
		foreach ( $time_log[0] as $day_key => $day_log ) {

			$dates .= '"' . $day_key . '", ';
			$second_calculation = 0;

			if ( $day_log && isset( $day_log ) ) {

				foreach ( $day_log as $day_log_timestamp ) {
					$second_calculation += $day_log_timestamp['video_duration'];
				}

			}

			$minutes .= intval($second_calculation/60) . ', ';

		} // foreach

	} // if

	return array( $dates, $minutes );

}
