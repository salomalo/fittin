<?php
/**
 * Exit if accessed directly
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Create shortcode for Video background Pro
 *
 * @since 1.1.1
 *
 * @uses shortcode_atts()
 */
function vidbg_shortcode( $atts , $content = null ) {
	$loader_colors = vidbgpro_loader_colors();

	// Attributes
	extract(
		shortcode_atts(
			array(
				'container'								=> 'body',
				'type'										=> 'self-host',
				'mp4'											=> '#',
				'webm'										=> '#',
				'youtube_url'							=> '#',
				'poster'									=> '#',
				'muted' 									=> 'true',
				'loop' 										=> 'true',
				'overlay' 								=> 'false',
				'overlay_color' 					=> '#000',
				'overlay_alpha' 					=> '0.3',
				'overlay_texture_url' 		=> '',
				'frontend_play_button' 		=> 'false',
				'frontend_volume_button'	=> 'false',
				'frontend_position'				=> 'bottom-right',
			), $atts , 'vidbg'
		)
	);


	$output = "<script>
		jQuery(function($){
			var vidbgpro_shortcode_data = {
				containerValue: '" . $container . "',
				backgroundType: '" . $type . "',
				mp4Value: '" . $mp4 . "',
				webmValue: '" . $webm . "',
				youtubeURL: '" . $youtube_url . "',
				posterValue: '" . $poster . "',
				isMuted: " . $muted . ",
				isLoop: " . $loop . ",
				isOverlay: " . $overlay . ",
				overlayColor: '" . $overlay_color . "',
				overlayAlpha: '" . $overlay_alpha . "',
				overlayTexture: '" . $overlay_texture_url . "',
				isFrontendPlay: " . $frontend_play_button . ",
				isFrontendMute: " . $frontend_volume_button . ",
				frontendPosition: '" . $frontend_position . "',
				loaderBgColor: '" . $loader_colors['background-color'] . "',
				loaderColor: '" . $loader_colors['color'] . "',
			};

			if( vidbgpro_shortcode_data.backgroundType === 'youtube' ) {
				$(vidbgpro_shortcode_data.containerValue).vidbgYT({
					videoID: vidbgpro_shortcode_data.youtubeURL,
					poster: vidbgpro_shortcode_data.posterValue,
					mute: vidbgpro_shortcode_data.isMuted,
					repeat: vidbgpro_shortcode_data.isLoop,
					overlay: vidbgpro_shortcode_data.isOverlay,
					overlayColor: vidbgpro_shortcode_data.overlayColor,
					overlayAlpha: vidbgpro_shortcode_data.overlayAlpha,
					overlayTexture: vidbgpro_shortcode_data.overlayTexture,
					isFrontendPlay: vidbgpro_shortcode_data.isFrontendPlay,
					isFrontendMute: vidbgpro_shortcode_data.isFrontendMute,
					frontendPosition: vidbgpro_shortcode_data.frontendPosition,
					loaderBgColor: vidbgpro_shortcode_data.loaderBgColor,
					loaderColor: vidbgpro_shortcode_data.loaderColor,
				});
			} else {
				$(vidbgpro_shortcode_data.containerValue).vidbg({
					'mp4': vidbgpro_shortcode_data.mp4Value,
					'webm': vidbgpro_shortcode_data.webmValue,
					'poster': vidbgpro_shortcode_data.posterValue,
				}, {
					muted: vidbgpro_shortcode_data.isMuted,
					loop: vidbgpro_shortcode_data.isLoop,
					overlay: vidbgpro_shortcode_data.isOverlay,
					overlayColor: vidbgpro_shortcode_data.overlayColor,
					overlayAlpha: vidbgpro_shortcode_data.overlayAlpha,
					overlayTexture: vidbgpro_shortcode_data.overlayTexture,
					isFrontendPlay: vidbgpro_shortcode_data.isFrontendPlay,
					isFrontendMute: vidbgpro_shortcode_data.isFrontendMute,
					frontendPosition: vidbgpro_shortcode_data.frontendPosition,
					loaderBgColor: vidbgpro_shortcode_data.loaderBgColor,
					loaderColor: vidbgpro_shortcode_data.loaderColor,
				});
			}

		});
	</script>";

	return $output;
}
add_shortcode( 'vidbg', 'vidbg_shortcode' );
?>
