jQuery( document ).ready(function($) {

	// show advanced options on click
	$(function() {
		$('#vidbg_advanced_options').hide();
	  $(".cmb2-id-vidbg-metabox-field-advanced-button a").click(function(e) {
			e.preventDefault();
			if ($('#vidbg_advanced_options').css('display') === 'none') {
	    	$('#vidbg_advanced_options').show(500);
				$('a.advanced-options-button').text(vidbgpro_localized_text.hide_advanced);
			} else {
				$('#vidbg_advanced_options').hide(500);
				$('a.advanced-options-button').text(vidbgpro_localized_text.show_advanced);
			}
	  });
	});

	// SiteOrigin conditionals: overlay vidbg type
	$(function() {
		$(document).ajaxComplete(function() {

			function getField(inputType, fieldId, getParent, parentIndex) {
				if( getParent == true ) {
					return $(inputType + '[name="style[' + fieldId + ']"]').parents().eq(parentIndex);
				} else {
					return $(inputType + '[name="style[' + fieldId + ']"]');
				}
			}

			if( $('.so-sidebar select[name="style[vidbg_SO_type]"]').length ) {

				// check what vidbg type is set on refresh
				if( getField( 'select', 'vidbg_SO_type', false ).val() == 'youtube' ) {
					getField( 'input', 'vidbg_SO_youtube_url', true, 2 ).show();
					getField( 'input', 'vidbg_SO_mp4', true, 2 ).hide();
					getField( 'input', 'vidbg_SO_webm', true, 2 ).hide();
				} else {
					getField( 'input', 'vidbg_SO_youtube_url', true, 2 ).hide();
					getField( 'input', 'vidbg_SO_mp4', true, 2 ).show();
					getField( 'input', 'vidbg_SO_webm', true, 2 ).show();
				}

				// check what vidbg type is set on change
				getField( 'select', 'vidbg_SO_type', false ).bind( 'change', function(e) {
					if( $(this).val() == 'youtube' ) {
						getField( 'input', 'vidbg_SO_youtube_url', true, 2 ).show();
						getField( 'input', 'vidbg_SO_mp4', true, 2 ).hide();
						getField( 'input', 'vidbg_SO_webm', true, 2 ).hide();
					} else {
						getField( 'input', 'vidbg_SO_youtube_url', true, 2 ).hide();
						getField( 'input', 'vidbg_SO_mp4', true, 2 ).show();
						getField( 'input', 'vidbg_SO_webm', true, 2 ).show();
					}
				});

				// check if overlay is set on refresh
				if( getField( 'input', 'vidbg_SO_overlay', false ).is(':checked') ) {
					getField( 'input', 'vidbg_SO_overlay_color', true, 4 ).show();
					getField( 'input', 'vidbg_SO_overlay_alpha', true, 2 ).show();
					getField( 'input', 'vidbg_SO_overlay_texture_url', true, 3 ).show();
				} else {
					getField( 'input', 'vidbg_SO_overlay_color', true, 4 ).hide();
					getField( 'input', 'vidbg_SO_overlay_alpha', true, 2 ).hide();
					getField( 'input', 'vidbg_SO_overlay_texture_url', true, 3 ).hide();
				}

				// check if overlay is set on change
				getField( 'input', 'vidbg_SO_overlay', false ).change( function(e) {
					if( $(this).is(':checked') ) {
						getField( 'input', 'vidbg_SO_overlay_color', true, 4 ).show();
						getField( 'input', 'vidbg_SO_overlay_alpha', true, 2 ).show();
						getField( 'input', 'vidbg_SO_overlay_texture_url', true, 3 ).show();
					} else {
						getField( 'input', 'vidbg_SO_overlay_color', true, 4 ).hide();
						getField( 'input', 'vidbg_SO_overlay_alpha', true, 2 ).hide();
						getField( 'input', 'vidbg_SO_overlay_texture_url', true, 3 ).hide();
					}
				});
			}
		});
	});

	// show current video background type fields
	$(function() {
		if( $('#vidbg_metabox_field_type').val() == 'youtube' ) {
			$('.cmb2-id-vidbg-metabox-field-mp4,.cmb2-id-vidbg-metabox-field-webm').hide();
		} else {
			$('.cmb2-id-vidbg-metabox-field-youtube-url').hide();
		}

	  $('#vidbg_metabox_field_type').bind('change', function (e) {
			if( $(this).val() == 'self-host' ) {
				$('.cmb2-id-vidbg-metabox-field-youtube-url').hide(500);
				$('.cmb2-id-vidbg-metabox-field-mp4,.cmb2-id-vidbg-metabox-field-webm').show(500);
			} else {
				$('.cmb2-id-vidbg-metabox-field-youtube-url').show(500);
				$('.cmb2-id-vidbg-metabox-field-mp4,.cmb2-id-vidbg-metabox-field-webm').hide(500);
			}

	  });
	});

	// show extra overlay settings if enabled
  $(function(){
    $('#vidbg_metabox_field_overlay1, #vidbg_metabox_field_overlay2').bind('change', function (e) {
      if( $('#vidbg_metabox_field_overlay1').is(':checked')) {
        $('.cmb2-id-vidbg-metabox-field-overlay-color, .cmb2-id-vidbg-metabox-field-overlay-alpha, .cmb2-id-vidbg-metabox-field-overlay-texture').hide(500);
				$('.postbox-container .cmb-row:not(:last-of-type).cmb2-id-vidbg-metabox-field-overlay').css({
					'border-bottom' : '1px solid #e9e9e9',
				});
      }
      else if( $('#vidbg_metabox_field_overlay2').is(':checked')) {
        $('.cmb2-id-vidbg-metabox-field-overlay-color, .cmb2-id-vidbg-metabox-field-overlay-alpha, .cmb2-id-vidbg-metabox-field-overlay-texture').show(500);
				$('.postbox-container .cmb-row:not(:last-of-type).cmb2-id-vidbg-metabox-field-overlay').css({
					'border-bottom' : '0',
				});
      }
    }).trigger('change');
  });

	// Loop through all cmb-type-slider-field instances and instantiate the slider UI
	$( '.cmb-type-own-slider' ).each(function() {
		var $this       = $( this );
		var $value      = $this.find( '.own-slider-field-value' );
		var $slider     = $this.find( '.own-slider-field' );
		var $text       = $this.find( '.own-slider-field-value-text' );
		var $range			= $this.find( '.ui-slider-range' );
		var slider_data = $value.data();

		$slider.slider({
			range 	: 'min',
			value 	: slider_data.start,
			min   	: slider_data.min,
			animate : 'fast',
			max   	: slider_data.max,
			slide 	: function( event, ui ) {
				$value.val( ui.value );
				$text.text( ui.value );
			}
		});

		// Initiate the display
		$value.val( $slider.slider( 'value' ) );
		$text.text( $slider.slider( 'value' ) );

		$this.css({
			'visibility': 'visible',
		});
	});


	// Ajax used to permanently dismiss admin notices message
	jQuery(document).on( 'click', '.vidbgpro-admin-notice .notice-dismiss', function() {
    jQuery.ajax({
        url: ajaxurl,
        data: {
            action: 'vidbgpro_dismiss_notices'
        }
    })
	})

});


