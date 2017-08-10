<?php

function view_month( $time_log, $selected_month ) {

	$minutes = [];
	$dates = [];

	$current_month = date( 'm' );
	$current_month_nice = date( 'M' );
	$current_year = date('Y');

	if ( !empty( $selected_month ) ) {
		// $current_month = date( 'm', strtotime( $current_month . ' '. $selected_month . ' month') );
		$current_month_year = strtotime( date('Y-m-d') . ' ' . "$selected_month" . ' month');

		$current_month = date( 'm', $current_month_year );
		$current_month_nice = date( 'M', $current_month_year );

		$current_year = date( 'Y', $current_month_year );

		// if ( $current_month == '12' ) { // go back a year if going down to december
		// 	$current_year = date( 'Y', strtotime( $current_year . ' -1 year') )
		// }
	}

	if ( $time_log && isset( $time_log ) ) {

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

	return array( 'dates' => $dates, 'minutes' => $minutes, 'month' => $current_month_nice, 'year' => $current_year );

}
