<?php
/*
Plugin Name: Video Background Pro
Plugin URI: http://pushlabs.co/video-background-pro/
Description: Pro version of the popular Video Background plugin on the WordPress repo.
Version: 1.1.4
Author: Blake Wilson, Push Labs
Author URI: http://pushlabs.co
License: Push Labs Standard License
License URI: http://pushlabs.co/license/standard
Text Domain: video-background-pro
Domain Path: /languages
*/

/**
 * Exit if accessed directly
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define some constants
 */
define( 'VIDBGPRO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'VIDBGPRO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'VIDBGPRO_PLUGIN_BASE', plugin_basename(__FILE__) );
define( 'VIDBGPRO_PLUGIN_VERSION', '1.1.4' );

/**
 * Run on installation of plugin
 * Checks if we need to deactivate the free verison of the plugin
 * Deletes option to restore admin notices
 *
 * @since 1.0.0
 *
 * @uses is_plugin_active()
 * @uses deactivate_plugins()
 * @uses delete_option()
 */
function vidbgpro_install_plugin() {
	if( is_plugin_active( 'video-background/candide-vidbg.php') ) {
		deactivate_plugins( 'video-background/candide-vidbg.php' );
	}
	delete_option( 'vidbgpro-admin-notice-dismissed' );
}
register_activation_hook( __FILE__, 'vidbgpro_install_plugin' );

/**
 * Checks WordPress version
 *
 * @since 1.0.0
 *
 * @param $version string The WordPress version
 * @return boolean True or false
 */
function vidbgpro_is_wp_version( $version = '4.2' ) {
	global $wp_version;
	if ( version_compare( $wp_version, $version, '>=' ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Include neccesary classes and framework
 */
if( file_exists( VIDBGPRO_PLUGIN_PATH . 'inc/vendor/cmb2/init.php' ) ) {
	require_once( VIDBGPRO_PLUGIN_PATH . 'inc/vendor/cmb2/init.php' );
}
if( file_exists( VIDBGPRO_PLUGIN_PATH . 'inc/classes/cmb2_field_slider.php' ) ) {
	require_once( VIDBGPRO_PLUGIN_PATH . 'inc/classes/cmb2_field_slider.php' );
}
if( file_exists( VIDBGPRO_PLUGIN_PATH . 'inc/register_metaboxes.php' ) ) {
	require_once( VIDBGPRO_PLUGIN_PATH . 'inc/register_metaboxes.php' );
}
if( file_exists( VIDBGPRO_PLUGIN_PATH . 'inc/vidbgpro_shortcode.php' ) ) {
	require_once( VIDBGPRO_PLUGIN_PATH . 'inc/vidbgpro_shortcode.php' );
}
if( file_exists( VIDBGPRO_PLUGIN_PATH . 'inc/vidbg_shortcode.php' ) ) {
	require_once( VIDBGPRO_PLUGIN_PATH . 'inc/vidbg_shortcode.php' );
}
if( file_exists( VIDBGPRO_PLUGIN_PATH . 'inc/getting_started.php' ) ) {
	require_once( VIDBGPRO_PLUGIN_PATH . 'inc/getting_started.php' );
}
if( file_exists( VIDBGPRO_PLUGIN_PATH . 'inc/admin_notices.php' ) && ( vidbgpro_is_wp_version() == true ) ) {
	require_once( VIDBGPRO_PLUGIN_PATH . 'inc/admin_notices.php' );
}
if( class_exists( 'Vc_Manager' ) ) {
	require_once( VIDBGPRO_PLUGIN_PATH . 'inc/classes/vidbg_vc_integration.php' );
}
if( class_exists( 'SiteOrigin_Panels_Css_Builder' ) ) {
	require_once( VIDBGPRO_PLUGIN_PATH . 'inc/classes/vidbg_siteorigin_integration.php' );
}

/**
 * Load the plugin text domain for localization
 *
 * @since 1.0.0
 * @link https://codex.wordpress.org/I18n_for_WordPress_Developers
 *
 * @uses load_plugin_textdomain()
 */
function vidbgpro_load_textdomain() {
	load_plugin_textdomain( 'video-background-pro', false, VIDBGPRO_PLUGIN_BASE . '/languages' );
}
add_action( 'plugins_loaded', 'vidbgpro_load_textdomain' );

/**
 * Enqueue admin scripts and styles in the backend
 *
 * @since 1.0.0
 *
 * @uses wp_enqueue_style()
 * @uses wp_enqueue_script()
 */
function vidbgpro_enqueue_admin_scripts() {
	wp_enqueue_style( 'vidbgpro-metabox-style', VIDBGPRO_PLUGIN_URL . 'assets/css/vidbgpro-backend.css', array(), VIDBGPRO_PLUGIN_VERSION );
	wp_enqueue_script( 'vidbgpro-admin-backend', VIDBGPRO_PLUGIN_URL . 'assets/js/vidbgpro-backend.js', array( 'jquery' ), VIDBGPRO_PLUGIN_VERSION );

	wp_localize_script( 'vidbg-admin-backend', 'vidbgpro_localized_text', array(
		'show_advanced' => __( 'Show Advanced Options', 'video-background-pro' ),
		'hide_advanced' => __( 'Hide Advanced Options', 'video-background-pro' ),
	) );
}
add_action( 'admin_enqueue_scripts', 'vidbgpro_enqueue_admin_scripts' );
add_action( 'siteorigin_panel_enqueue_admin_scripts', 'vidbgpro_enqueue_admin_scripts' );

/**
 * Enqueue Video Background Premium scripts and styles for the frontend
 *
 * @since 1.0.0
 *
 * @uses wp_enqueue_script()
 * @uses wp_enqueue_style()
 */
function vidbgpro_enqueue_frontend_scripts() {
	wp_enqueue_script( 'vidbgpro', VIDBGPRO_PLUGIN_URL . 'assets/js/vidbgpro.min.js', array( 'jquery' ), VIDBGPRO_PLUGIN_VERSION, true );
	wp_enqueue_style( 'vidbgpro-frontend-style', VIDBGPRO_PLUGIN_URL . 'assets/css/vidbgpro-frontend.css', array(), VIDBGPRO_PLUGIN_VERSION );
}
add_action('wp_enqueue_scripts', 'vidbgpro_enqueue_frontend_scripts' );

/**
 * Add custom colors to the CMB2 colorpicker
 *
 * @since 1.0.0
 *
 * @param $l10n array Localize color pickers in CMB2 for custom colors
 * @return array Array of custom colors for the CMB2 colorpicker
 */
function vidbgpro_custom_color_palette( $l10n ) {
	$l10n['defaults']['color_picker'] = array(
		'palettes' => array( '#000000', '#3498db', '#e74c3c', '#374e64', '#2ecc71', '#f1c40f' ),
	);
	return $l10n;
}
add_filter( 'cmb2_localized_data', 'vidbgpro_custom_color_palette' );

/**
 * Add loading screen colors array
 *
 * @since 1.1.2
 *
 * @uses apply_filters()
 * @return array Array of colors for loading element
 */
function vidbgpro_loader_colors() {
	$colors = array();
	$colors['background-color'] = '#232323';

	/**
	 * @TODO Add the color attribute
	 */
	$colors['color'] = '#ffffff';

	$colors = apply_filters( 'vidbgpro_loading_colors', $colors );
	return $colors;
}

/**
 * Add post types function for filtering
 *
 * @since 1.1.2
 *
 * @uses apply_filters()
 * @return array Array of post types Video Background Pro uses
 */
function vidbgpro_post_types() {
	$post_types = array( 'post', 'page' );
	$post_types = apply_filters( 'vidbgpro_post_types', $post_types );

	return $post_types;
}

/**
 * Add Video Background initialize to the footer.
 *
 * @since 1.0.0
 *
 * @uses is_page()
 * @uses is_single()
 * @uses is_home()
 * @uses get_option()
 * @uses get_the_ID()
 * @uses get_post_meta()
 */
function vidbgpro_init_footer() {
	if( is_page() || is_single() || is_home() && get_option( 'show_on_front') == 'page' ) {

		if( is_page() || is_single() ) {
			$the_id = get_the_ID();
		} elseif( is_home() && get_option( 'show_on_front' ) == 'page' ) {
			$the_id = get_option( 'page_for_posts' );
		}

		$container_field = get_post_meta( $the_id, 'vidbg_metabox_field_container', true );
		$background_type_field = get_post_meta( $the_id, 'vidbg_metabox_field_type', true );
		$mp4_field = get_post_meta( $the_id, 'vidbg_metabox_field_mp4', true );
		$webm_field = get_post_meta( $the_id, 'vidbg_metabox_field_webm', true );
		$youtube_url_field = get_post_meta( $the_id , 'vidbg_metabox_field_youtube_url', true );
		$poster_field = get_post_meta( $the_id, 'vidbg_metabox_field_poster', true );
		$overlay = get_post_meta( $the_id, 'vidbg_metabox_field_overlay', true );
		$overlay_color = get_post_meta( $the_id, 'vidbg_metabox_field_overlay_color', true );
		$overlay_alpha = get_post_meta( $the_id, 'vidbg_metabox_field_overlay_alpha', true );
		$overlay_texture = get_post_meta( $the_id, 'vidbg_metabox_field_overlay_texture', true );
		$no_loop_field = get_post_meta( $the_id, 'vidbg_metabox_field_no_loop', true );
		$unmute_field = get_post_meta( $the_id, 'vidbg_metabox_field_unmute', true );
		$frontend_play_button_field = get_post_meta( $the_id, 'vidbg_metabox_field_frontend_play', true );
		$frontend_mute_button_field = get_post_meta( $the_id, 'vidbg_metabox_field_frontend_mute', true );
		$buttons_position_field = get_post_meta( $the_id, 'vidbg_metabox_field_frontend_buttons_position', true );

		$select_type = ( $background_type_field == 'youtube' ) ? 'youtube' : 'self-host';
		$boolean_mute = ( $unmute_field == 'on' ) ? 'false' : 'true';
		$boolean_loop = ( $no_loop_field == 'on' ) ? 'false' : 'true';
		$boolean_overlay = ( $overlay == 'on' ) ? 'true' : 'false';
		$boolean_frontend_play = ( $frontend_play_button_field == 'on' ) ? 'true' : 'false';
		$boolean_frontend_mute = ( $frontend_mute_button_field == 'on' ) ? 'true' : 'false';
		$overlay_color_value = !empty( $overlay_color ) ? $overlay_color : '#000';
		$overlay_alpha_value = !empty( $overlay_alpha ) ? '0.' . $overlay_alpha : '0.3';
		$overlay_texture_uri = !empty( $overlay_texture ) ? $overlay_texture : '';
		$buttons_position = 'bottom-right';
		if ( $buttons_position_field !== '' ) {
			$buttons_position = $buttons_position_field;
		}
		$loader_colors = vidbgpro_loader_colors();

		if( !empty( $container_field ) ) { ?>
			<script type="text/javascript">
				jQuery(function($){
					var vidbgpro_metabox_data = {
						containerValue: '<?php echo $container_field; ?>',
						backgroundType: '<?php echo $select_type; ?>',
						mp4Value: '<?php echo $mp4_field; ?>',
						webmValue: '<?php echo $webm_field; ?>',
						youtubeURL: '<?php echo $youtube_url_field; ?>',
						posterValue: '<?php echo $poster_field; ?>',
						isMuted: <?php echo $boolean_mute; ?>,
						isLoop: <?php echo $boolean_loop; ?>,
						isOverlay: <?php echo $boolean_overlay; ?>,
						overlayColor: '<?php echo $overlay_color_value; ?>',
						overlayAlpha: '<?php echo $overlay_alpha_value; ?>',
						overlayTexture: '<?php echo $overlay_texture_uri; ?>',
						isFrontendPlay: <?php echo $boolean_frontend_play; ?>,
						isFrontendMute: <?php echo $boolean_frontend_mute; ?>,
						frontendPosition: '<?php echo $buttons_position; ?>',
						loaderBgColor: '<?php echo $loader_colors['background-color']; ?>',
						loaderColor: '<?php echo $loader_colors['color']; ?>',
					};

					if( vidbgpro_metabox_data.backgroundType === 'youtube' ) {
						$(vidbgpro_metabox_data.containerValue).vidbgYT({
							videoID: vidbgpro_metabox_data.youtubeURL,
							poster: vidbgpro_metabox_data.posterValue,
							mute: vidbgpro_metabox_data.isMuted,
							repeat: vidbgpro_metabox_data.isLoop,
							overlay: vidbgpro_metabox_data.isOverlay,
							overlayColor: vidbgpro_metabox_data.overlayColor,
							overlayAlpha: vidbgpro_metabox_data.overlayAlpha,
							overlayTexture: vidbgpro_metabox_data.overlayTexture,
							isFrontendPlay: vidbgpro_metabox_data.isFrontendPlay,
							isFrontendMute: vidbgpro_metabox_data.isFrontendMute,
							frontendPosition: vidbgpro_metabox_data.frontendPosition,
							loaderBgColor: vidbgpro_metabox_data.loaderBgColor,
							loaderColor: vidbgpro_metabox_data.loaderColor,
						});
					} else {
						$(vidbgpro_metabox_data.containerValue).vidbg({
							'mp4': vidbgpro_metabox_data.mp4Value,
							'webm': vidbgpro_metabox_data.webmValue,
							'poster': vidbgpro_metabox_data.posterValue,
						}, {
							muted: vidbgpro_metabox_data.isMuted,
							loop: vidbgpro_metabox_data.isLoop,
							overlay: vidbgpro_metabox_data.isOverlay,
							overlayColor: vidbgpro_metabox_data.overlayColor,
							overlayAlpha: vidbgpro_metabox_data.overlayAlpha,
							overlayTexture: vidbgpro_metabox_data.overlayTexture,
							isFrontendPlay: vidbgpro_metabox_data.isFrontendPlay,
							isFrontendMute: vidbgpro_metabox_data.isFrontendMute,
							frontendPosition: vidbgpro_metabox_data.frontendPosition,
							loaderBgColor: vidbgpro_metabox_data.loaderBgColor,
							loaderColor: vidbgpro_metabox_data.loaderColor,
						});
					}
				});
			</script>
		<?php }
	}
}
add_action( 'wp_footer', 'vidbgpro_init_footer' );
