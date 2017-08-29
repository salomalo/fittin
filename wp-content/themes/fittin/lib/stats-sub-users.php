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
				$gMemSql = "SELECT * FROM ".$wpdb -> prefix."group_sets_members WHERE group_id = '".$result->id."' ORDER BY createdDate";
				$gMemResults = $wpdb -> get_results($gMemSql);

$output = '';

				foreach( $gMemResults as $member ) { ?>

					<div>
						<?php $member_info = get_user_by( 'ID', $member->member_id  );

						// echo '<h4>' . $member_info->display_name . '</h4>';
						$member_stats = get_user_meta( $member->member_id, 'time_list' ); ?>
						<ul>
							<?php
							$stats_array = array();
							foreach ( $member_stats[0] as $stat ) {
								array_push( $stats_array[date('Y-m-d')][], $stat );

								array_push( $stats_array[date('Y-m-d')][], $stat );

print_r($stats_array);
								// echo '<li>' . date( 'd-m-Y H:i', $stat ) . '</li>';
							}
							?>
						</ul>
					</div>
				<?php }
			}

			return $output;

		} else {
			// not a group leader
			return 'not a group leader';
		}
	}
}
