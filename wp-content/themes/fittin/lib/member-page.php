<?php

add_shortcode( 'member-page', function() {

    $user = MM_User::getCurrentWPUser();
    $membership = $user->getMembershipName();
    // echo '<pre>';
    // print_r($user);
    // echo '</pre>';

    // Free
    if ( 'Teacher' == $membership ) {
        $output = '17 days of free trial remaining. <a href="/buy-packages">Buy Full Version Now</a>';
    }
    // Gold
    if ( 'Gold' == $membership ) {
        $output = 'Please use this link to allow users to sign up to your group:<br>';
        $output .= '<a href="';
        $output .= do_shortcode( '[MM_Group_SignUp_Link]' );
        $output .= '">Sign Up Link</a>';
    }



    return $output;

});
