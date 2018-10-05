<?php


// $first_day = date( 'd-m-Y', strtotime( 'last monday -1 days' )); // mon
// $last_day = date( 'd-m-Y', strtotime( 'last monday +6 days' )); // sat

function all_stats( $first_day, $last_day ) {

    $args = array(
        'fields' => 'all',
        'no_found_rows' => true,
        'role__in' => array( 'Group Leader', 'subscriber', 'administrator' )
    );
    $user_query = new WP_User_Query( $args );

   	// User Loop
	$x = 0; // counter
	if ( ! empty( $user_query->results ) ) {

		$output = '<h4>' . date( 'jS M Y', strtotime( $first_day )) . '  to  ' . date( 'jS M Y', strtotime( $last_day )) . '</h4><br>'. '<table><tr><td style="border: 1px solid #555; padding: 2px 4px; font-weight:bold">School account</td><td style="border: 1px solid #555; padding: 2px 4px; font-weight:bold">Video views</td><td style="border: 1px solid #555; padding: 2px 4px; font-weight:bold">Active teacher sub accounts</td></tr>';

        foreach ( $user_query->results as $user ) {

            $log = get_user_meta( $user->ID, 'time_list', true);

            if ( 'Group Leader' == $user->roles[0] ) {

                $school_grand_total = 0;
                $added_log_times = add_log_times( $log, $first_day, $last_day, $user->data->display_name, true, $school_grand_total );  // put in grand total

                // ===================================
                // Get group leader's sub users' stats
                // ===================================

                global $wpdb;
                $sql = "SELECT id, group_name FROM " . $wpdb -> prefix . "group_sets WHERE group_leader = '" . $user->ID . "'";
                $result	= $wpdb -> get_row($sql);


                $active_teacher_count = 0;
                $all_teacher_count = 0;
                if ( $result ) {

                    // now get users from group
                    $gMemSql = "SELECT * FROM " . $wpdb -> prefix . "group_sets_members WHERE group_id = '" . $result->id . "' ORDER BY createdDate";
                    $gMemResults = $wpdb -> get_results($gMemSql);
                    // echo '<pre>'.print_r($gMemResults,true).'</pre>';

                    foreach( $gMemResults as $member ) {
                        $member_details = get_user_by( 'ID', $member->member_id );
                        $member_log = get_user_meta( $member->member_id, 'time_list', true );
                        $added_log_times = add_log_times( $member_log, $first_day, $last_day, $member_details->data->display_name, false, $school_grand_total ); // put in grand total
                    
                        $school_grand_total = $added_log_times['grand_total']; // get out new g total
                        $added_log_times['grand_total'] != 0 ? $active_teacher_count++ : '' ;
                        $all_teacher_count++;
                    }
                } // if results
                $output .= '<tr><td style="border: 1px solid #555; padding: 2px 4px">' . $user->ID . ' ' . $user->data->display_name . '</td><td style="border: 1px solid #555; padding: 2px 4px">' . round( $added_log_times['grand_total'] / 60 ) . ' mins' . '</td><td style="border: 1px solid #555; padding: 2px 4px">' . $active_teacher_count . '/' . $all_teacher_count . '</td></tr>';

            } 
          
            $x++;
        } // foreach user

        $output .= '</table>';
        return $output;

    } // if there are users


}