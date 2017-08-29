<?php
?>
<button class="stats default-view current">All Stats</button>
<button class="stats week-view">Week View</button>
<button class="stats month-view">Month View</button>
<?php
$time_log = get_user_meta( get_current_user_id(), 'time_list' );
$recent = get_user_meta( get_current_user_id(), 'time_list_most_recent', true );
$user_info = get_userdata( get_current_user_id() );

echo get_sub_users( $user_info );

// ==================
// Get data for chart
// ==================

$datesminutes = view_default( $time_log );
$dates = implode( '", "', $datesminutes['dates'] );
$dates = '"' . $dates . '"';
$minutes = implode( ', ', $datesminutes['minutes'] );
// $week = view_week( $time_log, null );

do_action( 'modify_dates_minutes' );

// dummy data
// ----------------------------------------
// $minutes = "11, 5, 0, 5, 2, 5, 5, 3, 6";
// $minutes2 = "11, 5, 0, 5, 2";
// $dates = "10-07-2017, 11-07-2017, 12-07-2017, 13-07-2017, 14-07-2017, 15-07-2017, 16-07-2017, 17-07-2017, 18-07-2017, 19-07-2017";
// -----------------------------------------

// echo '<pre style="background:grey">';
// print_r($dates);
// echo "<br>";
// print_r($minutes);
// echo '</pre>';

// ==================
// Display Chart
// ==================

echo '<div class="fittin-chart"><h4></h4>'; ?>

<canvas id="fittinChart" width="1200" height="400"></canvas>
<script>
var ctx = document.getElementById("fittinChart").getContext('2d');
var fittinChart = new Chart(ctx, {
    type: 'line',
    data: {
		labels : [<?php echo $dates ?>],
		datasets : [
			{
				label 			: "Video views (minutes)" ,
				// backgroundColor	: "#663ff2",
				backgroundColor	: "rgba(255,0,0,0.3)",
				data 			: [<?php echo $minutes ?>]
			},
			// {
			// 	label 			: "Shubbadubba" ,
			// 	backgroundColor	: "rgba(255,255,0,0.3)",
			// 	data 			: [<?php echo $minutes2 ?>]
			// },
		]
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

echo '<div class="chart-nav" data-timestamp=' . time() . ' data-week="0" data-month="0"><span class="prev hide">Previous</span><span class="divider hide"> || </span><span class="next hide">Next</span></div><div class="hide chart-loading">ABRAABRACADAB</div></div>';

?>

<?php
