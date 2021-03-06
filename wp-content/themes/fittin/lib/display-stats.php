<?php ?>
<button class="stats default-view current">All Stats</button>
<button class="stats week-view">Week View</button>
<button class="stats month-view">Month View</button>
<div class="stats-modal"></div>
<?php
// $recent = get_user_meta( get_current_user_id(), 'time_list_most_recent', true );
$user_info = get_userdata( get_current_user_id() );
$sub_users = get_sub_users( $user_info );
$time_log = $sub_users['time_log'];
if ( false === $sub_users )  { // if no sub users
	$time_log = get_user_meta( get_current_user_id(), 'time_list', true );
} else { ?>
	<div class="subusers">
		<button id="all-subusers" class="current stats subusers">All users</button><button class="stats subusers" data-id="<?php echo get_current_user_id(); ?>"><?php echo $user_info->data->display_name; ?>
		<?php foreach ( $sub_users['subuser_info'] as $sub_user ) {
			echo '<button class="stats subusers" data-id="' . $sub_user['id'] . '">' . $sub_user['name'] . '</button>';
		} ?>
	</div>
<?php }
// echo '<pre>' . print_r( $time_log, true ), '</pre> || <br><Br><br><br>';
// echo '<pre>' . print_r( $time_log2, true ), '</pre>';

// ==================
// Get data for chart
// ==================

$datesminutes = view_default( $time_log );

// prepare dates
$dates = implode( '", "', $datesminutes['dates'] );
$dates = '"' . $dates . '"';

// prepare minutes
$minutes = implode( ', ', $datesminutes['minutes'] );


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

display_chart( $dates, $minutes );
