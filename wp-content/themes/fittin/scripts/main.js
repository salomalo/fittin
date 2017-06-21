jQuery(document).ready(function($){

    var iframe = $('iframe.vimeo-card');
    var player = new Vimeo.Player(iframe);

	player.getVideoTitle().then(function(title) {
        console.log('title:', title);
    });

    player.on('play', function() {
        console.log('played the video!');

		var data = {
			'action': 'my_action',
			'user': ajax_object.user_id
		};

		jQuery.post(ajax_object.ajax_url, data, function(response) {
			console.log('Got this from the server: ' + response);
		});

	});

});
