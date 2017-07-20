jQuery(document).ready(function($){

	// ============
	// record stats
	// ============

	if ($('iframe.vimeo-card').length > 0) {
		var iframe = $('iframe.vimeo-card');
	} else if ($('.pp_fade iframe').length > 0) {
		var iframe = $('.pp_fade iframe');
	}

	if ($('iframe.vimeo-card').length > 0 || $('.pp_fade iframe').length > 0) {
	    var player = new Vimeo.Player(iframe);
		player.getVideoTitle().then(function(title) {
	        console.log('title:', title);
	    });
	    player.on('play', function() {
			player.getDuration().then(function(length) {
				player.getVideoId().then(function(videoid) {

					var data = {
						'action': 'record_stats',
						'user': ajax_object.user_id,
						'video_length': length,
						'video_id': videoid
					};

					jQuery.post(ajax_object.ajax_url, data, function(response) {
						console.log('Got this from the server: ' + response);
					});

				}).catch(function(error) {
				    console.log('getVideoId error: '+error);
				});
			}).catch(function(error) {
			    console.log('getDuration error: '+error);
			});
		});
	}

	// ================
	// set up week view
	// ================

	$('.week-view').click(function(e){

		e.preventDefault();
		var weekData = {
			'action': 'week_view_button',
			'user': ajax_object.user_id
		};

		jQuery.post(ajax_object.ajax_url, weekData, function(response) {
			var datesMinutes = JSON.parse(response);
			// console.log(datesMinutes);
			$('.fittin-chart h4').html('Week commencing '+datesMinutes['week_commencing']);

			// $('.fittin-chart').html(newChart);
			fittinChart.data.labels = datesMinutes['dates'];
			fittinChart.data.datasets[0].data = datesMinutes['minutes'];
			fittinChart.update();
		});
	});

});
