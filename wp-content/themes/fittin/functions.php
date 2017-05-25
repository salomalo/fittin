<?php

/**
 * @package Fitt-In
 * @since Fitt-In 1.0
 *
 * Functions
 *
 */

include( 'lib/member-page.php' );

add_action( 'init', 'woo_custom_move_navigation', 10 );
function woo_custom_move_navigation () {
 // Remove main nav from the woo_header_after hook
 remove_action( 'woo_header_after','woo_nav', 10 );
 // Add main nav to the woo_header_inside hook
 add_action( 'woo_header_inside','woo_nav', 10 );
 } // End woo_custom_move_navigation()


// stop wp removing div tags
function ikreativ_tinymce_fix( $init )
{
    // html elements being stripped
    $init['extended_valid_elements'] = 'div[*]';

    // pass back to wordpress
    return $init;
}
add_filter('tiny_mce_before_init', 'ikreativ_tinymce_fix');



// Add Shortcode
function favs_shortcode() {

}
add_shortcode( 'Favs', 'favs_shortcode' );




// custom admin style sheet
function my_admin_head() {
	$whodat = get_current_user_id();
	if ($whodat != 2) {
        echo '<link href="https://fittin.wpengine.com/wp-content/themes/fittin/group-leader-style.css" rel="stylesheet" type="text/css">';
    }
}
// add_action('admin_head', 'my_admin_head');

// ======
// Scripts
// =======

function fittin_scripts() {
    if ( is_user_logged_in() ) {
        // try this to specify video pages? if( 'index.php' != $hook ) {
        wp_enqueue_script( 'fittin-main', get_stylesheet_directory_uri() . '/scripts/main.js', 'jquery', '1.0.0', true );
        wp_localize_script( 'fittin-main', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'user_id' => get_current_user_id() ) );
    }
}
// add_action('wp_enqueue_scripts', 'fittin_scripts');

// ============
// AJAX Handler
// ============

function ajax_handler() {

	global $wpdb; // this is how you get access to the database

	$user = intval( $_POST['user'] );
    $time = intval( $_POST['time'] );

    $time_list = get_user_meta( $user, 'time_list', true );
    if ( '' == $time_list ) {
        $time_list = array( $time );
    }

    array_push( $time_list, $time );

    update_user_meta( $user, 'time_list', $time_list );

    wp_die();

}
// add_action( 'wp_ajax_my_action', 'ajax_handler' );
