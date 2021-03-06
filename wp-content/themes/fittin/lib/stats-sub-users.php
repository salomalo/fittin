<?php

function get_sub_users( $user_info ) {
	foreach( $user_info->roles as $role ) {
		if ( 'Group Leader' === $role ) { // if group leader
			global $wpdb;

			// get group leader's group id
			$sql = "SELECT id, group_name FROM " . $wpdb -> prefix . "group_sets WHERE group_leader = '" . get_current_user_id() . "'";
			$result	= $wpdb -> get_row($sql);
			if ( count( $result ) > 0 ) {

				// now get users from group
				$gMemSql = "SELECT * FROM ".$wpdb -> prefix . "group_sets_members WHERE group_id = '".$result->id."' ORDER BY createdDate";
				$gMemResults = $wpdb -> get_results($gMemSql);

				$new_time_log = [];
				$subuser_info = [];
				foreach( $gMemResults as $member ) {

					$subuser  = get_user_by( 'ID', $member->member_id  );
					$subuser_info[]  = array(
						'name' => $subuser->data->display_name,
						'id' => $subuser->ID,

					);

					$member_stats = get_user_meta( $member->member_id, 'time_list', true );
					$stats_array = array();
					if ( !empty( $member_stats ) ) {
						foreach ( $member_stats as $key => $stat ) {
							if ( empty( $new_time_log[$key] ) ) {
								$new_time_log[$key] = $stat;
							} else {
								foreach ( $stat as $single ) {
									$new_time_log[$key][] = $single;
								}
							}
						} // foreach memstat as $stat
					}
				} // foreach gmem as $member
			} // if results counts > 0

			$output = array( 'time_log' => $new_time_log, "subuser_info" => $subuser_info );
			return $output;

		} else {
			// not a group leader
			return false;
		}
	}
}
