jQuery(document).ready(function($){

	if ($('iframe.vimeo-card').length > 0) {
		var iframe = $('iframe.vimeo-card');
	} else if ($('.pp_fade iframe').length > 0) {
		var iframe = $('.pp_fade iframe');
	}

	if ($('iframe.vimeo-card').length > 0 || $('.pp_fade iframe').length > 0) {

	    var player = new Vimeo.Player(iframe);

		player.getVideoTitle().then(function(title) {
	        // console.log('title:', title);
	    });

	    player.on('play', function() {
	        // console.log('played the video!');

			var data = {
				'action': 'my_action',
				'user': ajax_object.user_id
			};

			jQuery.post(ajax_object.ajax_url, data, function(response) {
				// console.log('Got this from the server: ' + response);
			});

		});

	}

});
