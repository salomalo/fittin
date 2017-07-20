<?php

$time_log = get_user_meta( get_current_user_id(), 'time_list' );
$recent = get_user_meta( get_current_user_id(), 'time_list_most_recent', true );
$user_info = get_userdata( get_current_user_id() );

foreach( $user_info->roles as $role ) {
	if ( 'Group Leader' === $role ) { // if group leader

		// get group leader's group id
		$sql = "SELECT id, group_name FROM " . $wpdb -> prefix . "group_sets WHERE group_leader = '" . get_current_user_id() . "'";
		$result	= $wpdb -> get_row($sql);
		if ( count( $result ) > 0 ) {

			// now get users from group
			$gMemSql = "SELECT * FROM ".$wpdb -> prefix."group_sets_members WHERE group_id = '".$result->id."' ORDER BY createdDate";
			$gMemResults = $wpdb -> get_results($gMemSql);

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

							// echo '<li>' . date( 'd-m-Y H:i', $stat ) . '</li>';
						}
						// var_dump($stats_array);
						?>
					</ul>
				</div>
			<?php }
		}

	} else {
		// not a group leader
	}
}

// ==================
// Get data for chart
// ==================

$datesminutes = view_standard( $time_log );
$dates = $datesminutes[0];
$minutes = $datesminutes[1];
$week = view_week( $time_log );

do_action( 'modify_dates_minutes' );

// dummy data
// ----------------------------------------
// $minutes = "11, 5, 0, 5, 2, 5, 5, 3, 6";
// $dates = "10-07-2017, 11-07-2017, 12-07-2017, 13-07-2017, 14-07-2017, 15-07-2017, 16-07-2017, 17-07-2017, 18-07-2017, 19-07-2017";
// -----------------------------------------

// ==================
// Display Chart
// ==================

$minutesperdate = '[wp_charts title="Minutes Exercised" type="line" margin="10px 0px 20px 0px" datasets="'. $minutes . '" labels="' . $dates . '" scaleFontSize="12" scaleoverride="true" scalesteps="15" scalestepwidth="1" scalestartvalue="0" canvaswidth="1100px" canvasheight="366px" relativewidth="3" width="1100px" height="366px" colours="#663ff2" ]';

echo '<div class="fittin-chart"><h4></h4>';
// echo do_shortcode($minutesperdate);
?>
<canvas id="fittinChart" width="1200" height="400"></canvas>
<script>
var ctx = document.getElementById("fittinChart").getContext('2d');
var fittinChart = new Chart(ctx, {
    type: 'line',
    data: {
		labels : [
			<?php echo $dates ?>
		],
		datasets : [{
			label 			: "Video views (minutes)" ,
			backgroundColor	: "#663ff2",
			data 			: [<?php echo $minutes ?>]
		}]
    },
    options: {
		lineTension: 1,
		pointBackgroundColor: "f0f",
		scales: {
			yAxes: [{
				ticks: {
					beginAtZero: true,
					fixedStepSize: 1
				}
			}]
	    }
    }
});
</script>


<?php

echo '</div>';

?>

<button class="week-view">Week View</button>

<?php
