<?php
/**
 * Exit if accessed directly
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for Visual Composer integration with Video Background
 * Creates shortcode 'vidbgVC' and returns the jQuery init for Video Background
 * Adds Video Background params to Visual Composer
 *
 * @since 1.0.0
 */
class vidbgpro_VisualComposer {

	/**
	 * Create the construct function for class init
	 *
	 * @since 1.0.0
	 *
	 * @uses add_action()
	 */
	function __construct() {
		add_action( 'vc_before_init', array( $this, 'vidbgpro_integrateWithVC' ) );
	}

	/**
	 * Create the vidbgVC shortcode
	 *
	 * @since 1.0.0
	 * @param $atts array Array of shortcode attributes
	 * @param $content string Content between the shortcode
	 * @return jQuery Video Background init
	 * @access public
	 * @static
	 *
	 * @uses shortcode_atts()
	 * @uses wp_get_attachment_image_src()
	 */
	public static function vidbgpro_register_vidbgVC( $atts, $content = null ) {
		$type = $mp4 = $webm = $youtube_url = $poster = $play_audio = $disable_loop = $overlay = $overlay_color = $overlay_alpha = $overlay_texture_url = $frontend_play = $frontend_volume = $frontend_position = '';
		$output = '';

		/* create a random id */
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		$length = 14;
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		$vidbg_section_id = 'vidbg-section-ref-' . $randomString;

		$loader_colors = vidbgpro_loader_colors();

		extract(
			shortcode_atts(
				array(
					'type'                    => 'self-host',
					'mp4'                     => '#',
					'webm'                    => '#',
					'youtube_url'             => '#',
					'poster'                  => '#',
					'play_audio'              => 'false',
					'disable_loop'            => 'false',
					'overlay'                 => 'false',
					'overlay_color'           => '#000',
					'overlay_alpha'           => '0.3',
					'overlay_texture_url'     => '',
					'frontend_play'           => 'false',
					'frontend_volume'         => 'false',
					'frontend_position'       => 'bottom-right',
				), $atts , 'vidbgVC'
			)
		);

		$boolean_mute = ( $play_audio == 'false' ) ? 'true' : 'false';
		$boolean_loop = ( $disable_loop == 'false' ) ? 'true' : 'false';

		$poster_src = wp_get_attachment_image_src( $poster, 'full' );
		$texture_src = wp_get_attachment_image_src( $overlay_texture_url, 'full' );

		if( $mp4 !== '#' || $webm !== '#' || $youtube_url !== '#' ) {
			$output .= "<script>
				jQuery(function($){
					$('." . $vidbg_section_id . "').next('.vc_row').addClass('" . $vidbg_section_id . "');
  				var vidbgpro_vc_data = {
						containerValue: $('.vc_row." . $vidbg_section_id . "'),
						backgroundType: '" . $type . "',
						mp4Value: '" . $mp4 . "',
						webmValue: '" . $webm . "',
						youtubeURL: '" . $youtube_url . "',
						posterValue: '" . $poster_src[0] . "',
						isMuted: " . $boolean_mute . ",
						isLoop: " . $boolean_loop . ",
						isOverlay: " . $overlay . ",
						overlayColor: '" . $overlay_color . "',
						overlayAlpha: '" . $overlay_alpha . "',
						overlayTexture: '" . $texture_src[0] . "',
						isFrontendPlay: " . $frontend_play . ",
						isFrontendMute: " . $frontend_volume . ",
						frontendPosition: '" . $frontend_position . "',
						loaderBgColor: '" . $loader_colors['background-color'] . "',
						loaderColor: '" . $loader_colors['color'] . "',
					};

					if( vidbgpro_vc_data.backgroundType === 'youtube' ) {
						$(vidbgpro_vc_data.containerValue).vidbgYT({
							videoID: vidbgpro_vc_data.youtubeURL,
							poster: vidbgpro_vc_data.posterValue,
							mute: vidbgpro_vc_data.isMuted,
							repeat: vidbgpro_vc_data.isLoop,
							overlay: vidbgpro_vc_data.isOverlay,
							overlayColor: vidbgpro_vc_data.overlayColor,
							overlayAlpha: vidbgpro_vc_data.overlayAlpha,
							overlayTexture: vidbgpro_vc_data.overlayTexture,
							isFrontendPlay: vidbgpro_vc_data.isFrontendPlay,
							isFrontendMute: vidbgpro_vc_data.isFrontendMute,
							frontendPosition: vidbgpro_vc_data.frontendPosition,
							loaderBgColor: vidbgpro_vc_data.loaderBgColor,
							loaderColor: vidbgpro_vc_data.loaderColor,
						});
					} else {
						$(vidbgpro_vc_data.containerValue).vidbg({
							'mp4': vidbgpro_vc_data.mp4Value,
							'webm': vidbgpro_vc_data.webmValue,
							'poster': vidbgpro_vc_data.posterValue,
						}, {
							muted: vidbgpro_vc_data.isMuted,
							loop: vidbgpro_vc_data.isLoop,
							overlay: vidbgpro_vc_data.isOverlay,
							overlayColor: vidbgpro_vc_data.overlayColor,
							overlayAlpha: vidbgpro_vc_data.overlayAlpha,
							overlayTexture: vidbgpro_vc_data.overlayTexture,
							isFrontendPlay: vidbgpro_vc_data.isFrontendPlay,
							isFrontendMute: vidbgpro_vc_data.isFrontendMute,
							frontendPosition: vidbgpro_vc_data.frontendPosition,
							loaderBgColor: vidbgpro_vc_data.loaderBgColor,
							loaderColor: vidbgpro_vc_data.loaderColor,
						});
					}
				});
			</script>";
			$output .= '<div class="' . $vidbg_section_id . '" style="display: none;"></div>';
		}

		return $output;
	}

	/**
	 * Add the Video Background params to the vc_row VC element
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @uses __()
	 * @uses vc_add_params()
	 */
	public function vidbgpro_integrateWithVC() {
		$attributes = array(
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Video background type', 'video-background-pro' ),
				'param_name'  => 'type',
				'description' => __( 'Please specify if you would like to self host your video background or use a YouTube video instead.', 'video-background-pro' ),
				'value' => array(
					__( 'Self Host', 'video-background-pro' ) => 'self-host',
					__( 'YouTube', 'video-background-pro' )   => 'youtube',
				),
				'group'       => __( 'Video Background', 'video-background-pro' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Link to .mp4', 'video-background-pro' ),
				'param_name'  => 'mp4',
				'description' => __( 'Please specify the link to the .mp4 file.', 'video-background-pro' ),
				'dependency'  => array(
					'element' => 'type',
					'value'   => 'self-host',
				),
				'group'       => __( 'Video Background', 'video-background-pro' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Link to .webm', 'video-background-pro' ),
				'param_name'  => 'webm',
				'description' => __( 'Please specify the link to the .webm file.', 'video-background-pro' ),
				'dependency'  => array(
					'element' => 'type',
					'value'   => 'self-host',
				),
				'group'       => __( 'Video Background', 'video-background-pro' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Link to YouTube video', 'video-background-pro' ),
				'param_name'  => 'youtube_url',
				'description' => __( 'Please specify the link to the YouTube video.', 'video-background-pro' ),
				'dependency'  => array(
					'element' => 'type',
					'value'   => 'youtube',
				),
				'group'       => __( 'Video Background', 'video-background-pro' ),
			),
			array(
				'type'        => 'attach_image',
				'heading'     => __( 'Fallback Image', 'video-background-pro' ),
				'param_name'  => 'poster',
				'description' => __( 'Please upload a fallback image.', 'video-background-pro' ),
				'group'       => __( 'Video Background', 'video-background-pro' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Enable Overlay?', 'video-background-pro' ),
				'param_name'  => 'overlay',
				'description' => __( 'Add an overlay over the video. This is useful if your text isn\'t readable with a video background.', 'video-background-pro' ),
				'group'       => __( 'Video Background', 'video-background-pro' ),
				'value'       => '0',
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => __( 'Overlay Color', 'video-background-pro' ),
				'param_name'  => 'overlay_color',
				'value'       => '#000',
				'description' => __( 'If overlay is enabled, a color will be used for the overlay. You can specify the color here.', 'video-background-pro' ),
				'dependency'  => array(
					'element' => 'overlay',
					'value'   => 'true',
				),
				'group'       => __( 'Video Background', 'video-background-pro' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Overlay Opacity', 'video-background-pro' ),
				'param_name'  => 'overlay_alpha',
				'value'       => '0.3',
				'description' => __( 'Specify the opacity of the overlay. Accepts any value between 0.00-1.00 with 0 being completely transparent and 1 being completely invisible. Ex. 0.30', 'video-background-pro' ),
				'dependency'  => array(
					'element' => 'overlay',
					'value'   => 'true',
				),
				'group'       => __( 'Video Background', 'video-background-pro' ),
			),
			array(
				'type'        => 'attach_image',
				'heading'     => __( 'Overlay Texture', 'video-background-pro' ),
				'param_name'  => 'overlay_texture_url',
				'description' => __( 'If you would like to display a custom pattern for an overlay, you can do so here.', 'video-background-pro' ),
				'dependency'  => array(
					'element' => 'overlay',
					'value'   => 'true',
				),
				'group'       => __( 'Video Background', 'video-background-pro' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Disable Loop?', 'video-background-pro' ),
				'param_name'  => 'disable_loop',
				'description' => __( 'Turn off the loop for Video Background. Once the video is complete, it will display the last frame of the video.', 'video-background-pro' ),
				'group'       => __( 'Video Background', 'video-background-pro' ),
				'value'       => '0',
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Play the Audio?', 'video-background-pro' ),
				'param_name'  => 'play_audio',
				'description' => __( 'Enabling this will play the audio of the video.', 'video-background-pro' ),
				'group'       => __( 'Video Background', 'video-background-pro' ),
				'value'       => '0',
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Enable play/pause button on the frontend?', 'video-background-pro' ),
				'description' => __( 'By enabling this option, a play/pause button will show up in the bottom right hand corner of the video background.', 'video-background-pro' ),
				'param_name'  => 'frontend_play',
				'group'       => __( 'Video Background', 'video-background-pro' ),
				'value'       => '0',
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Eanble mute/unmute button on the frontend?', 'video-background-pro' ),
				'description' => __( 'By enabling this option, a mute/unmute button will show up in the bottom right hand corner of the video background.', 'video-background-pro' ),
				'param_name'  => 'frontend_volume',
				'group'       => __( 'Video Background', 'video-background-pro' ),
				'value'       => '0',
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Frontend Buttons Position', 'video-background-pro' ),
				'description' => __( 'Please select the position you would like your frontend buttons to be in. (If applicable)', 'video-background-pro' ),
				'param_name' => 'frontend_position',
				'group' => __( 'Video Background', 'video-background-pro' ),
				'value' => array(
					__( 'Bottom Right', 'video-background-pro' )   => 'bottom-right',
					__( 'Top Right', 'video-background-pro' ) => 'top-right',
					__( 'Bottom left', 'video-background-pro' )   => 'bottom-left',
					__( 'Top left', 'video-background-pro' )   => 'top-left',
				),
				'default' => 'bottom-right',
			),
		);
		vc_add_params( 'vc_row', $attributes );
	}
}

$init_vidbgpro_for_visualcomposer = new vidbgpro_VisualComposer();


/**
 * Adds the shortcode output before the VC row
 *
 * @since 1.0.0
 * @param $atts array Array of shortcode attributes
 * @param $content string Content between the shortcode
 * @return object VC integration class with shortcode
 *
 * @uses vidbgpro_register_vidbgVC()
 */
if ( !function_exists( 'vc_theme_before_vc_row' ) ) {
	function vc_theme_before_vc_row($atts, $content = null) {
		return vidbgpro_VisualComposer::vidbgpro_register_vidbgVC($atts, $content);
	}
}
?>
