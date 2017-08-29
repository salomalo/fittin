jQuery(document).ready(function($){
	var state = 'default';
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
		$('.chart-nav').attr('data-week', 0); // reset prev/next week
		$('.stats.subusers').removeClass('current');
		$('#all-subusers').addClass('current');
		weekView(e);
	});
	$('.prev').click(function(e){
		if (state == 'week') {
			var newTime = parseInt($('.chart-nav').attr('data-timestamp'));
			newTime = newTime - (60*60*24*7);

			$('.chart-nav').attr('data-timestamp',newTime);
			$('.chart-nav').attr('data-week', parseInt($('.chart-nav').attr('data-week')) -1 );

			weekView(e,newTime);
		}
	});
	$('.next').click(function(e){
		if (state == 'week' && $('.chart-nav').attr('data-week') < 0) {
			var newTime = parseInt($('.chart-nav').attr('data-timestamp'));
			newTime = newTime + (60*60*24*7);

			$('.chart-nav').attr('data-timestamp',newTime);
			$('.chart-nav').attr('data-week', parseInt($('.chart-nav').attr('data-week'))+1 );

			weekView(e,newTime);
		}
	});

	function weekView(e,newTime,subuser) {
		e.preventDefault();

		state = 'week';

		$('.chart-loading').removeClass('hide');
		if ($(this).hasClass('current')) {
			return;
			console.log('current');
		} else {
			var weekData = {
				'action': 'week_view_button',
				'user': ajax_object.user_id,
				'subuser' : subuser,
				'selected_week' : newTime
			};

			jQuery.post(ajax_object.ajax_url, weekData, function(response) {
				// console.log(response);
				var datesMinutes = JSON.parse(response);

				$('.fittin-chart .prev').removeClass('hide');
				$('.fittin-chart .next').removeClass('hide');
				$('.fittin-chart .divider').removeClass('hide');

				$('.chart-loading').addClass('hide');
				console.log(datesMinutes);
				$('.fittin-chart h4').html('Week commencing '+datesMinutes['week_commencing']);

				// $('.fittin-chart').html(newChart);
				fittinChart.data.labels = datesMinutes['dates'];
				fittinChart.data.datasets[0].data = datesMinutes['minutes'];
				fittinChart.update();
				$('.default-view').removeClass('current');
				$('.week-view').addClass('current');
				$('.month-view').removeClass('current');
			});
		}
	}

	// ================
	// Month view
	// ================

	$('.month-view').click(function(e){
		$('.chart-nav').attr('data-month', 0); // reset prev/next months
		$('.stats.subusers').removeClass('current');
		$('#all-subusers').addClass('current');
		monthView(e);
	});
	$('.prev').click(function(e){
		if (state == 'month') {
			var newTime = parseInt($('.chart-nav').attr('data-month')) -1;
			$('.chart-nav').attr('data-month', newTime);
			monthView(e,newTime);
		}
	});
	$('.next').click(function(e){
		if (state == 'month' && $('.chart-nav').attr('data-month') < 0) {
			var newTime = parseInt($('.chart-nav').attr('data-month'))+1;
			$('.chart-nav').attr('data-month', newTime);
			monthView(e,newTime);
		}
	});
	function monthView(e,newTime,subuser) {
		e.preventDefault();
		state = 'month';

		$('.chart-loading').removeClass('hide');
		if ($(this).hasClass('current')) {
			return;
			console.log('current');
		} else {
			var monthData = {
				'action': 'month_view_button',
				'user': ajax_object.user_id,
				'subuser' : subuser,
				'selected_month' : newTime
			};

			jQuery.post(ajax_object.ajax_url, monthData, function(response) {
				var datesMinutes = JSON.parse(response);
// console.log(datesMinutes);
				$('.chart-loading').addClass('hide');

				$('.prev').removeClass('hide');
				$('.next').removeClass('hide');
				$('.divider').removeClass('hide');
				$('.fittin-chart h4').html(datesMinutes['month'] + ', ' +datesMinutes['year']);

				fittinChart.data.labels = datesMinutes['dates'];
				fittinChart.data.datasets[0].data = datesMinutes['minutes'];
				fittinChart.update();
				$('.default-view').removeClass('current');
				$('.week-view').removeClass('current');
				$('.month-view').addClass('current');

			});
		}
	}

	// ================
	// Back to default view
	// ================

	$('.default-view').click(function(e){
		$('.stats.subusers').removeClass('current');
		$('#all-subusers').addClass('current');
		defaultView(e);
	});

	function defaultView(e,subuser) {
		e.preventDefault();
		state = 'default';

		$('.chart-loading').removeClass('hide');
		if ($(this).hasClass('current')) {
			return;
		} else {
			var defaultData = {
				'action': 'default_view_button',
				'user': ajax_object.user_id,
				'subuser' : subuser
			};

			jQuery.post(ajax_object.ajax_url, defaultData, function(response) {
				var datesMinutes = JSON.parse(response);
				$('.chart-loading').addClass('hide');
				$('.prev').addClass('hide');
				$('.next').addClass('hide');
				$('.divider').addClass('hide');

				console.log(datesMinutes);
				$('.fittin-chart h4').html('');

				fittinChart.data.labels = datesMinutes['dates'];
				fittinChart.data.datasets[0].data = datesMinutes['minutes'];
				fittinChart.update();
				$('.default-view').addClass('current');
				$('.week-view').removeClass('current');
				$('.month-view').removeClass('current');

			});
		}
	}

	// ==========
	// sub users
	// ==========

	$('.stats.subusers').click(function(e){

		$('.stats.subusers').removeClass('current');
		$(this).addClass('current');

		if ( state == 'month' ) {
			if (e.target.id == 'all-subusers') {
				monthView(e);
			} else {
				monthView(e,null,e.target.attributes['data-id'].value);
			}
		} else if ( state == 'week' ) {
			if (e.target.id == 'all-subusers') {
				weekView(e);
			} else {
				weekView(e,null,e.target.attributes['data-id'].value);
			}
		} else if ( state == 'default' ) {
			if (e.target.id == 'all-subusers') {
				defaultView(e);
			} else {
				defaultView(e,e.target.attributes['data-id'].value);
			}
		}
	});

});
