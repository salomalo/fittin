<?php
/**
 * Template Name: Fitt-in Stats
 */

get_header();
?>
       
    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="col-full">
    
    	<div id="main-sidebar-container">    

            <!-- #main Starts -->
            <?php woo_main_before(); ?>
            <section id="main">   


                <?php 




                // steve c log
//                 echo print_r( get_current_user_id() );
//                 echo '<br>';
// echo '<pre>'.print_r( get_user_meta( 357, 'time_list', true ), true ) . '</pre><br><br>';

// global $wpdb;
// $sql = "SELECT id, group_name FROM " . $wpdb -> prefix . "group_sets WHERE group_leader = '" . 191 . "'";
// $result	= $wpdb -> get_row($sql);

// if ( $result ) {

//     // now get users from group
//     $gMemSql = "SELECT * FROM " . $wpdb -> prefix . "group_sets_members WHERE group_id = '" . $result->id . "' ORDER BY createdDate";
//     $gMemResults = $wpdb -> get_results($gMemSql);
//     foreach( $gMemResults as $member ) {
//         // echo '<pre>'.print_r( $member, true ) . '</pre>';
//         // echo '<pre style="background: #fcc">'.print_r( get_user_meta( $member->member_id, 'time_list', true ), true ) . '</pre><br><br>';

//         $dates =  get_user_meta( $member->member_id, 'time_list', true );
//         if ($dates) {
//             foreach ( $dates as $date => $key ) {
//                 if ( strpos( $date, '2018' ) !== false  ) {
//                     echo $date . '<br>';
//                 }
//             }
//         }
//         echo '<hr>';

//     }
// } 




if ( current_user_can( 'manage_options' ) ) {

                // get each monday & sunday since june 2017
                $first_day = date( 'U', strtotime( '5 June 2017' ) );
                $diff = time() - $first_day;

                $days = $diff / DAY_IN_SECONDS;
                $weeks = $days / 7;
                $weeks_rounded = ceil( $weeks );

                // go through each week
                for ( $i = 0;  $i <= $weeks_rounded; $i++ ) {

                    if ($i === 0) {
                        $first_day_loop = $first_day;
                    } else {
                        $first_day_loop = $first_day + ( $i * 7 * DAY_IN_SECONDS );
                    }
                    $last_day_loop = $first_day_loop + ( 6 * DAY_IN_SECONDS );

                    echo all_stats(  date( 'd-m-Y', $first_day_loop ), date( 'd-m-Y', $last_day_loop ) );
                    echo '<br><hr><br>';
                }

} // is user can manage options i.e. is admin

                ?>
            </section><!-- /#main -->
            <?php woo_main_after(); ?>
    
            <?php get_sidebar(); ?>

		</div><!-- /#main-sidebar-container -->         

		<?php get_sidebar( 'alt' ); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>

<?php get_footer(); ?>