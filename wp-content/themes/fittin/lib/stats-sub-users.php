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
				foreach( $gMemResults as $member ) {

					$member_info = get_user_by( 'ID', $member->member_id  );
					$member_stats = get_user_meta( $member->member_id, 'time_list', true );
					$stats_array = array();
					foreach ( $member_stats as $key => $stat ) {
						if ( empty( $new_time_log[$key] ) ) {
							$new_time_log[$key] = $stat;
						} else {
							foreach ( $stat as $single ) {
								$new_time_log[$key][] = $single;
							}
						}
					} // foreach memstat as $stat
				} // foreach gmem as $member
			} // if results counts > 0

			return $new_time_log;

		} else {
			// not a group leader
			return false;
		}
	}
}
