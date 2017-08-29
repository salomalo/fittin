<?php

/**
 * @package Fitt-In
 * @since Fitt-In 1.0
 *
 * Functions
 *
 */

include( 'lib/member-page.php' );
include( 'lib/ajax-record-stats.php' );
include( 'lib/ajax-default.php' );
include( 'lib/ajax-week.php' );
include( 'lib/ajax-month.php' );

include( 'lib/view-default.php' );
include( 'lib/view-week.php' );
include( 'lib/view-month.php' );
include( 'lib/email-weekly-stats.php' );
include( 'lib/stats-sub-users.php' );
include( 'lib/display-chart.php' );


add_action( 'init', 'woo_custom_move_navigation', 10 );
function woo_custom_move_navigation () {
	// Remove main nav from the woo_header_after hook
	remove_action( 'woo_header_after','woo_nav', 10 );
	// Add main nav to the woo_header_inside hook
	add_action( 'woo_header_inside','woo_nav', 10 );
} // End woo_custom_move_navigation()


// stop wp removing div tags
add_filter('tiny_mce_before_init', 'ikreativ_tinymce_fix');
function ikreativ_tinymce_fix( $init ) {
    // html elements being stripped
    $init['extended_valid_elements'] = 'div[*]';

    // pass back to wordpress
    return $init;
}



// Add Shortcode
function favs_shortcode() {

}
add_shortcode( 'Favs', 'favs_shortcode' );




// custom admin style sheet
function group_leader_style() {
	if ( !current_user_can( 'edit_themes' ) ) {
        wp_enqueue_style( 'group-leader', get_stylesheet_directory_uri() . '/group-leader-style.css', '1.0.0' );
    }
}
add_action('wp_enqueue_scripts', 'group_leader_style');

// ======
// Scripts
// =======

function fittin_scripts() {
    if ( is_user_logged_in() ) {
		wp_enqueue_script( 'vimeo-player', "https://player.vimeo.com/api/player.js" );

        // try this to specify video pages? if( 'index.php' != $hook ) {
        wp_enqueue_script( 'fittin-main', get_stylesheet_directory_uri() . '/scripts/main.js', array('jquery','vimeo-player'), '1.0.0', true );
        wp_localize_script( 'fittin-main', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'user_id' => get_current_user_id() ) );

    }
}
add_action('wp_enqueue_scripts', 'fittin_scripts');

// ========
// Date sort
// ========

function datediff( $a, $b ) {
	$a = date( 'U' , $a[0] );
	$b = date( 'U' , $b[0] );

	if ( $a == $b ) $r = 0;
	else $r = ( $a > $b ) ? 1: -1;

	return $r;
}
