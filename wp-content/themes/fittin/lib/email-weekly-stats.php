<?php

wp_schedule_event( time(), 'daily', 'fittin_weekly_email' );

// add_action( 'wp_footer', function() {
add_action( 'fittin_weekly_email', function() {

	// check it's sunday
	if ( 'Sun' !== date( 'D' ) ) {
		// return; @TODO
	}

	// Force one per day! This was firing multiple times
	$emails_last_sent_date = get_option( 'fittin_emails_last_sent_date' );
	if ( date( 'd-m-Y' ) == $emails_last_sent_date  ) {
		return;
	} else if ( empty( $emails_last_sent_date ) ) {
		update_option( 'fittin_emails_last_sent_date',  date( 'd-m-Y' ), $autoload );
	}



	$args = array(
		 'fields' => 'all',
		//  'fields' => 'id',
		 'no_found_rows' => true,
		 'role__in' => array( 'Group Leader', 'subscriber', 'administrator' )
	);
	$user_query = new WP_User_Query( $args );

	// User Loop
	$x = 0; // counter
	if ( ! empty( $user_query->results ) ) {

		$admin_email_output = '<table><tr><td style="border: 1px solid #555; padding: 2px 4px; font-weight:bold">School account</td><td style="border: 1px solid #555; padding: 2px 4px; font-weight:bold">Video views</td><td style="border: 1px solid #555; padding: 2px 4px; font-weight:bold">Active teacher accounts</td></tr>';
		$admin_emails = array();

		foreach ( $user_query->results as $user ) {

			$log = get_user_meta( $user->ID, 'time_list', true);

			// get start/end day  (run this job on a sunday)
			$first_day = date( 'd-m-Y', strtotime( 'last monday -7 days' )); // mon
			$last_day = date( 'd-m-Y', strtotime( 'last monday -2 days' )); // sat


// test value overrides
// $first_day = date( 'U', strtotime( 'last monday -9999 days' ) );
// $last_day = date('U');

			$output = "<img width='80' src='https://www.fitt-in.co.uk/wp-content/uploads/2017/03/logo.png' alt='Fitt-In' style='margin-bottom: 20px'><p>Hello " . $user->data->display_name . " (" . $user->roles[0] . "),</p>";

			if ( 'Group Leader' == $user->roles[0] ) {

				// =============
				// Group Leader
				// =============

				$school_grand_total = 0;
				$output .= '<p>Please find your school&#39;s video views for the week below.';

				// ====================
				// Group leader stats
				// ====================

				$added_log_times = add_log_times( $log, $first_day, $last_day, $user->data->display_name, true, $school_grand_total );  // put in grand total
				$output .= $added_log_times['log_table'];

				// ===================================
				// Get group leader's sub users' stats
				// ===================================

				global $wpdb;
				$sql = "SELECT id, group_name FROM " . $wpdb -> prefix . "group_sets WHERE group_leader = '" . $user->ID . "'";
				$result	= $wpdb -> get_row($sql);
				$active_teacher_count = 0;
				$all_teacher_count = 0;
				if ( count( $result ) > 0 ) {
					// now get users from group
					$gMemSql = "SELECT * FROM " . $wpdb -> prefix . "group_sets_members WHERE group_id = '" . $result->id . "' ORDER BY createdDate";
					$gMemResults = $wpdb -> get_results($gMemSql);
					foreach( $gMemResults as $member ) {
						$member_details = get_user_by( 'ID', $member->member_id );
						$member_log = get_user_meta( $member->member_id, 'time_list', true );
						$added_log_times = add_log_times( $member_log, $first_day, $last_day, $member_details->data->display_name, false, $school_grand_total ); // put in grand total
						$output .= $added_log_times['log_table'];
						$school_grand_total = $added_log_times['grand_total']; // get out new g total
						$added_log_times['grand_total'] != 0 ? $active_teacher_count++ : '' ;
						$all_teacher_count++;
					}
				} else {
					$output .= '<p>You don&#39;t have any teacher accounts associated with your school account.</p>';
				} // if results

				$output .= '<p><b>TOTAL VIDEO VIEWS for all users: ' . round( $added_log_times['grand_total'] / 60 ) . ' (mins).</b></p>'; // get out new g total

				$admin_email_output .= '<tr><td style="border: 1px solid #555; padding: 2px 4px">' . $user->data->display_name . '</td><td style="border: 1px solid #555; padding: 2px 4px">' . round( $added_log_times['grand_total'] / 60 ) . ' mins' . '</td><td style="border: 1px solid #555; padding: 2px 4px">' . $active_teacher_count . '/' . $all_teacher_count . '</td></tr>';

			} else if ( 'subscriber' == $user->roles[0] ) {

				// ==========
				// Subscriber
				// ==========

				$added_log_times = add_log_times( $log, $first_day, $last_day, $user->data->display_name, true, null );
				$output .= $added_log_times['log_table'];

				// ==========
				// Admin
				// ==========

			} else if ( 'administrator' == $user->roles[0] ) {
				$admin_emails[] = array(
					'email' => $user->data->user_email,
					'name'  => $user->data->display_name
				);
			}

			$output .= '<p>Kind regards,<br>Fitt-in</p>';
			$headers = 'From: Fitt-In <no-reply@fitt-in.co.uk>' . "\r\n";

			// ==========
			// Send email
			// ==========

			if ( 'Group Leader' == $user->roles[0] || 'subscriber' == $user->roles[0] ) {
				if ( 'cpd@loopmill.com' == $user->data->user_email ) {
					wp_mail( $user->data->user_email, 'Your video views this week', $output, $headers );
				}
				echo $output;
			}

			$x++;
		} // foreach user

		foreach( $admin_emails as $admin_email ) {

			$admin_single_email_output = '<div>Hi ' . $admin_email['name'] . ', please find the video view stats below.' . $admin_email_output . '</table> Kind regards, Fitt In</div>';

			if ( 'cpd@loopmill.com' == $admin_email['email'] ) {
				wp_mail( $admin_email['email'], 'Fitt in video views this week', $admin_single_email_output, $headers );
			}
			echo $admin_single_email_output;

		}

	}
});

add_filter( 'wp_mail_content_type', function() {
	return "text/html";
});

function add_log_times( $log, $first_day, $last_day, $name, $single, $grand_total ) {

	if ( !empty( $log ) ) {
		$y = 0; // counter
		$output = "<table style='margin-bottom:20px; border-collapse: collapse' cellspacing='0' cellpadding='0'><tr><td style='font-weight: bold'>Date</td><td style='font-weight: bold'>". $name . "&#39;s Video views (mins)</td></tr>";

		if ( true == $single ) {
			$output .= '<p>Please find your video views for the week below.</p>';
		}

		$total_time = 0;
		foreach( $log as $key => $value ) {
			$uni_key = strtotime($key);

			// if falls within given week
			if ( date( 'U', $uni_key ) > $first_day && date( 'U', $uni_key ) < $last_day ) {
				$time = 0;
				foreach ( $value as $entry ) {
					$time += $entry['video_duration'];
					$total_time += $entry['video_duration'];
					if ( is_int( $grand_total ) ) { $grand_total += $entry['video_duration']; }
				}

				$output .= "<tr><td style='border:1px solid #333; padding: 2px 4px;'>". date( 'D jS F, Y', $uni_key ) . "</td><td style='border:1px solid #333; padding: 2px 4px;'>" . round( $time / 60 ) . " mins</td></tr>";

			}
			$y++;
		}
		$output .= "<tr><td style='border:1px solid #333; padding: 2px 4px; font-weight:bold'>TOTAL</td><td style='border:1px solid #333; padding: 2px 4px; font-weight:bold'>" . round( $total_time / 60 ) . " mins</td></tr></table>";

	} else {
		if ( true == $single ) {
			$output = '<p>You haven&#39;t viewed any videos this week.</p>';
		} else {
			$output = '<p><b>' . $name . '</b> - No video views this week.</p>';
		}
	}
	return [ 'log_table' => $output, 'grand_total' => $grand_total ];

}
