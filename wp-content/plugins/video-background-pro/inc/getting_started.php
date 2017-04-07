<?php
/**
 * Exit if accessed directly
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add the getting started page to the admin options page
 *
 * @since 1.0.0
 *
 * @uses add_options_page()
 */
function vidbgpro_add_gettingstarted_page() {
	add_options_page(
		'Video Background',
		'Video Background',
		'manage_options',
		'vidbgpro',
		'vidbgpro_gettingstarted_page'
	);
}
add_action( 'admin_menu', 'vidbgpro_add_gettingstarted_page' );

/**
 * Getting started page content
 *
 * @TODO finish instructions
 * @since 1.0.0
 *
 * @uses _e()
 */
function vidbgpro_gettingstarted_page() {
	echo '<div class="wrap">';
		_e( '<h2>Video Background <span class="vidbgpro-tag">Pro</span></h2>', 'video-background-pro' );
		_e( '<p>Thank you for purchasing Video Background Pro!</p>', 'video-background-pro' );
		_e( '<h3>Getting Started</h3>', 'video-background-pro' );
		_e( '<p>Video background Pro makes it easy to add responsive, great looking video backgrounds to any element on your website. There are four ways to implement a video background with the Video Background Pro plugin:</p>', 'video-background-pro' );
		echo '<ol>';
			_e( '<li>Using the metabox</li>', 'video-background-pro' );
			_e( '<li>Using the Video Background Pro integration with Visual Composer</li>', 'video-background-pro' );
			_e( '<li>Using the Video Background Pro integration with Page Builder by SiteOrigin</li>', 'video-background-pro' );
			_e( '<li>Using the shortcode.</li>', 'video-background-pro' );
		echo '</ol>';
		_e( '<p>For full documentation, instructions, and video tutorials, please visit <a href="http://pushlabs.co/documentation/video-background-pro" target="_blank">the documentation</a>.</p>', 'video-background-pro' );
		_e( '<h3>Need Help?</h3>', 'video-background-pro' );
		_e( '<p>If you need assistance with Video Background Pro, you can <a href="http://pushlabs.co/support" target="_blank">submit a ticket</a>.</p>', 'video-background-pro' );
		_e( ' <a href="https://twitter.com/intent/follow?screen_name=blakewilsonme" class="button button-primary vidbg-twitter" target="_blank">Get Updates on Twitter</a>', 'video-background-pro' );
	echo '</div>';
}

/**
 * Add getting started link to the plugin's page
 *
 * @since 1.0.0
 * @param @links string Getting started link
 * @return string link on the plugin's page.
 *
 * @uses __()
 */
function vidbgpro_gettingstarted_link($links) {
	$gettingstarted_link = __( '<a href="options-general.php?page=vidbgpro">Getting Started</a>', 'video-background-pro' );
	array_unshift($links, $gettingstarted_link);
	return $links;
}
add_filter('plugin_action_links_' . VIDBGPRO_PLUGIN_BASE, 'vidbgpro_gettingstarted_link' );
