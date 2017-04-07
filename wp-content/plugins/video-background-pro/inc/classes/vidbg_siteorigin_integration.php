<?php
/**
 * Exit if accessed directly
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for SiteOrigin Page Builder integration with Video Background
 * Adds Video Background fields to Page Builder row
 *
 * @since 1.0.0
 */
class vidbgpro_SiteOrigin {

	/**
	 * Create the construct function for class init
	 *
	 * @since 1.0.0
	 *
	 * @uses add_filter()
	 */
	function __construct() {
		add_filter( 'siteorigin_panels_row_style_fields', array( $this, 'vidbgpro_SO_register_fields' ) );
		add_filter( 'siteorigin_panels_row_style_groups', array( $this, 'vidbgpro_SO_create_group' ), 10, 3 );
		add_filter( 'siteorigin_panels_before_row', array( $this, 'vidbgpro_SO_init_after_row' ), 10, 3 );
	}

	/**
	 * Adds the output before the Page Builder row
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Return Video Background jQuery init
	 *
	 * @uses wp_get_attachment_image_src()
	 */
	public function vidbgpro_SO_init_after_row( $output, $grid_item, $grid_attributes ) {
		$bgtype = array_key_exists( 'vidbg_SO_type', $grid_item['style'] ) ? $grid_item['style']['vidbg_SO_type'] : '';
		$mp4 = array_key_exists( 'vidbg_SO_mp4', $grid_item['style'] ) ? $grid_item['style']['vidbg_SO_mp4'] : '';
		$webm = array_key_exists( 'vidbg_SO_webm', $grid_item['style'] ) ? $grid_item['style']['vidbg_SO_webm'] : '';
		$youtube_url = array_key_exists( 'vidbg_SO_youtube_url', $grid_item['style'] ) ? $grid_item['style']['vidbg_SO_youtube_url'] : '';
		$poster = array_key_exists( 'vidbg_SO_poster', $grid_item['style'] ) ? $grid_item['style']['vidbg_SO_poster'] : '';
		$overlay = array_key_exists( 'vidbg_SO_overlay', $grid_item['style'] ) ? $grid_item['style']['vidbg_SO_overlay'] : '';
		$overlay_color = array_key_exists( 'vidbg_SO_overlay_color', $grid_item['style'] ) ? $grid_item['style']['vidbg_SO_overlay_color'] : '#000';
		$overlay_alpha = array_key_exists( 'vidbg_SO_overlay_alpha', $grid_item['style'] ) ? $grid_item['style']['vidbg_SO_overlay_alpha'] : '0.3';
		$overlay_texture = array_key_exists( 'vidbg_SO_overlay_texture_url', $grid_item['style'] ) ? $grid_item['style']['vidbg_SO_overlay_texture_url'] : '';
		$disable_loop = array_key_exists( 'vidbg_SO_disable_loop', $grid_item['style'] ) ? $grid_item['style']['vidbg_SO_disable_loop'] : '';
		$play_audio = array_key_exists( 'vidbg_SO_play_audio', $grid_item['style'] ) ? $grid_item['style']['vidbg_SO_play_audio'] : '';
		$frontend_play = array_key_exists( 'vidbg_SO_frontend_play', $grid_item['style'] ) ? $grid_item['style']['vidbg_SO_frontend_play'] : '';
		$frontend_volume = array_key_exists( 'vidbg_SO_frontend_volume', $grid_item['style'] ) ? $grid_item['style']['vidbg_SO_frontend_volume'] : '';
		$frontend_buttons_position = array_key_exists( 'vidbg_SO_frontend_position', $grid_item['style'] ) ? $grid_item['style']['vidbg_SO_frontend_position'] : '';

		$poster_src = wp_get_attachment_image_src( $poster, 'full' );
		$texture_src = wp_get_attachment_image_src( $overlay_texture, 'full' );

		$loader_colors = vidbgpro_loader_colors();

		$boolean_type = ( $bgtype === 'youtube' ) ? 'youtube' : 'self-host';

		$boolean_mute = ( $play_audio == false ) ? 'true' : 'false';
		$boolean_loop = ( $disable_loop == false ) ? 'true' : 'false';
		$boolean_overlay = ( $overlay == true ) ? 'true' : 'false';
		$boolean_frontend_play = ( $frontend_play == 'true' ) ? 'true' : 'false';
		$boolean_frontend_volume = ( $frontend_volume == 'true' ) ? 'true' : 'false';

		if ( $frontend_buttons_position === '' ) {
			$frontend_buttons_position === 'bottom-right';
		}

		/* create a random id */
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		$length = 14;
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		$vidbg_section_id = 'vidbg-so-ref-' . $randomString;

		if( $mp4 !== '' || $webm !== '' || $youtube_url !== '' ) {
			$output .= "<script>
				jQuery(function($){
					$('." . $vidbg_section_id . "').next('.panel-grid').addClass('" . $vidbg_section_id . "');

					if( $('.panel-grid." . $vidbg_section_id . " > .siteorigin-panels-stretch').length ) {
						var getContainerEl = $('.panel-grid." . $vidbg_section_id . " > .siteorigin-panels-stretch');
					} else {
						var getContainerEl = $('.panel-grid." . $vidbg_section_id . "');
					}

					var vidbgpro_so_data = {
						containerValue: getContainerEl,
						backgroundType: '" . $boolean_type . "',
						mp4Value: '" . $mp4 . "',
						webmValue: '" . $webm . "',
						youtubeURL: '" . $youtube_url . "',
						posterValue: '" . $poster_src[0] . "',
						isMuted: " . $boolean_mute . ",
						isLoop: " . $boolean_loop . ",
						isOverlay: " . $boolean_overlay . ",
						overlayColor: '" . $overlay_color . "',
						overlayAlpha: '" . $overlay_alpha . "',
						overlayTexture: '" . $texture_src[0] . "',
						isFrontendPlay: " . $boolean_frontend_play . ",
						isFrontendMute: " . $boolean_frontend_volume . ",
						frontendPosition: '" . $frontend_buttons_position . "',
						loaderBgColor: '" . $loader_colors['background-color'] . "',
						loaderColor: '" . $loader_colors['color'] . "',
					};

					if( vidbgpro_so_data.backgroundType === 'youtube' ) {
						$(vidbgpro_so_data.containerValue).vidbgYT({
							videoID: vidbgpro_so_data.youtubeURL,
							poster: vidbgpro_so_data.posterValue,
							mute: vidbgpro_so_data.isMuted,
							repeat: vidbgpro_so_data.isLoop,
							overlay: vidbgpro_so_data.isOverlay,
							overlayColor: vidbgpro_so_data.overlayColor,
							overlayAlpha: vidbgpro_so_data.overlayAlpha,
							overlayTexture: vidbgpro_so_data.overlayTexture,
							isFrontendPlay: vidbgpro_so_data.isFrontendPlay,
							isFrontendMute: vidbgpro_so_data.isFrontendMute,
							frontendPosition: vidbgpro_so_data.frontendPosition,
							loaderBgColor: vidbgpro_so_data.loaderBgColor,
							loaderColor: vidbgpro_so_data.loaderColor,
						});
					} else {
						$(vidbgpro_so_data.containerValue).vidbg({
							'mp4': vidbgpro_so_data.mp4Value,
							'webm': vidbgpro_so_data.webmValue,
							'poster': vidbgpro_so_data.posterValue,
						}, {
							muted: vidbgpro_so_data.isMuted,
							loop: vidbgpro_so_data.isLoop,
							overlay: vidbgpro_so_data.isOverlay,
							overlayColor: vidbgpro_so_data.overlayColor,
							overlayAlpha: vidbgpro_so_data.overlayAlpha,
							overlayTexture: vidbgpro_so_data.overlayTexture,
							isFrontendPlay: vidbgpro_so_data.isFrontendPlay,
							isFrontendMute: vidbgpro_so_data.isFrontendMute,
							frontendPosition: vidbgpro_so_data.frontendPosition,
							loaderBgColor: vidbgpro_so_data.loaderBgColor,
							loaderColor: vidbgpro_so_data.loaderColor,
						});
					}
				});
			</script>";
			$output .= '<div class="' . $vidbg_section_id . '" style="display: none;"></div>';
		}

		return $output;
	}

	/**
	 * Create SiteOrigin group for Video Background on row
	 *
	 * @since 1.0.0
	 * @param $groups
	 * @param $post_id
	 * @param $args
	 * @return array Return group name to edit row
	 *
	 * @uses __()
	 */
	public function vidbgpro_SO_create_group( $groups, $post_id, $args ) {
		$groups['vidbg_SO'] = array(
			'name' => __('Video Background', 'video-background-pro'),
			'priority' => 25,
		);
		return $groups;
	}

	/**
	 * Add fields to SiteOrigin Page Builder row
	 *
	 * @since 1.0.0
	 * @access public
	 * @param $fields array Setup fields for Video Background
	 * @return array $fields Returns fields to SiteOrigin
	 *
	 * @uses __()
	 */
  public function vidbgpro_SO_register_fields($fields) {
		$priority = 5;
		$group_name = 'vidbg_SO';

		$fields['vidbg_SO_type'] = array(
			'name'        => __('Video Background Type', 'video-background-pro'),
			'type'        => 'select',
			'description' => __( 'Please specify if you would like to self host your video background or use a YouTube video instead.', 'video-background-pro' ),
			'options'     => array(
				'self-host' => __( 'Self Host', 'video-background-pro' ),
				'youtube'   => __( 'YouTube', 'video-background-pro' ),
			),
			'group'       => $group_name,
			'priority'    => $priority++,
		);
		$fields['vidbg_SO_mp4'] = array(
			'name'        => __( 'Link to .mp4', 'video-background-pro' ),
			'type'        => 'url',
			'description' => __( 'Please specify the link to the .mp4 file.', 'video-background-pro' ),
			'group'       => $group_name,
			'priority'    => $priority++,
		);
		$fields['vidbg_SO_webm'] = array(
			'type'        => 'url',
			'name'        => __( 'Link to .webm', 'video-background-pro' ),
			'description' => __( 'Please specify the link to the .webm file.', 'video-background-pro' ),
			'group'       => $group_name,
			'priority'    => $priority++,
		);
		$fields['vidbg_SO_youtube_url'] = array(
			'type'        => 'text',
			'name'        => __( 'Link to YouTube video', 'video-background-pro' ),
			'description' => __( 'Please specify the link to the YouTube video.', 'video-background-pro' ),
			'group'       => $group_name,
			'priority'    => $priority++,
		);
		$fields['vidbg_SO_poster'] = array(
			'type'        => 'image',
			'name'        => __( 'Fallback Image', 'video-background-pro' ),
			'description' => __( 'Please upload a fallback image.', 'video-background-pro' ),
			'group'       => $group_name,
			'priority'    => $priority++,
		);
		$fields['vidbg_SO_overlay'] = array(
			'type'        => 'checkbox',
			'name'        => __( 'Enable Overlay?', 'video-background-pro' ),
			'description' => __( 'Add an overlay over the video. This is useful if your text isn\'t readable with a video background.', 'video-background-pro' ),
			'group'       => $group_name,
			'priority'    => $priority++,
		);
		$fields['vidbg_SO_overlay_color'] = array(
			'type'        => 'color',
			'name'        => __( 'Overlay Color', 'video-background-pro' ),
			'description' => __( 'If overlay is enabled, a color will be used for the overlay. You can specify the color here.', 'video-background-pro' ),
			'default'     => '#000',
			'group'       => $group_name,
			'priority'    => $priority++,
		);
		$fields['vidbg_SO_overlay_alpha'] = array(
			'type'        => 'text',
			'name'        => __( 'Overlay Opacity', 'video-background-pro' ),
			'description' => __( 'Specify the opacity of the overlay. Accepts any value between 0.00-1.00 with 0 being completely transparent and 1 being completely invisible. Ex. 0.30', 'video-background-pro' ),
			'default'     => '0.3',
			'group'       => $group_name,
			'priority'    => $priority++,
		);
		$fields['vidbg_SO_overlay_texture_url'] = array(
			'type'        => 'image',
			'name'        => __( 'Overlay Texture', 'video-background-pro' ),
			'description' => __( 'If you would like to display a custom pattern for an overlay, you can do so here.', 'video-background-pro' ),
			'group'       => $group_name,
			'priority'    => $priority++,
		);
		$fields['vidbg_SO_disable_loop'] = array(
			'type'        => 'checkbox',
			'name'        => __( 'Disable Loop?', 'video-background-pro' ),
			'description' => __( 'Turn off the loop for Video Background. Once the video is complete, it will display the last frame of the video.', 'video-background-pro' ),
			'group'       => $group_name,
			'priority'    => $priority++,
		);
		$fields['vidbg_SO_play_audio'] = array(
			'type'        => 'checkbox',
			'name'        => __( 'Play the Audio?', 'video-background-pro' ),
			'description' => __( 'Enabling this will play the audio of the video.', 'video-background-pro' ),
			'group'       => $group_name,
			'priority'    => $priority++,
		);
		$fields['vidbg_SO_frontend_play'] = array(
			'type'        => 'checkbox',
			'name'        => __( 'Enable play/pause button on the frontend?', 'video-background-pro' ),
			'description' => __( 'By enabling this option, a play/pause button will show up in the bottom right hand corner of the video background.', 'video-background-pro' ),
			'group'       => $group_name,
			'priority'    => $priority++,
		);
		$fields['vidbg_SO_frontend_volume'] = array(
			'type'        => 'checkbox',
			'name'        => __( 'Eanble mute/unmute button on the frontend?', 'video-background-pro' ),
			'description' => __( 'By enabling this option, a mute/unmute button will show up in the bottom right hand corner of the video background.', 'video-background-pro' ),
			'group'       => $group_name,
			'priority'    => $priority++,
		);
		$fields['vidbg_SO_frontend_position'] = array(
			'type'        => 'select',
			'name'        => __( 'Frontend Buttons Position', 'video-background-pro' ),
			'description' => __( 'Please select the position you would like your frontend buttons to be in. (If applicable)', 'video-background-pro' ),
			'options'     => array(
				'bottom-right' => __( 'Bottom Right', 'video-background-pro' ),
				'top-right'   => __( 'Top Right', 'video-background-pro' ),
				'bottom-left'   => __( 'Bottom Left', 'video-background-pro' ),
				'top-left'   => __( 'Top Left', 'video-background-pro' ),
			),
			'group'       => $group_name,
			'priority'    => $priority++,
		);
		return $fields;
	}
}

$init_vidbgpro_for_siteorigin = new vidbgpro_SiteOrigin();
