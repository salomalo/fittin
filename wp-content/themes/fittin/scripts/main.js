jQuery(document).ready(function($){

	$('iframe.vimeo-card').each(function(){
		$(this).before('<div class="vimeo-ready"></div>');
	});

	$('.vimeo-ready').click(function(){

		var data = {
			'action': 'my_action',
			'user': ajax_object.user_id,
			'time': $.now()
		};

		jQuery.post(ajax_object.ajax_url, data, function(response) {
			// alert('Got this from the server: ' + response);
		});

		$(this).addClass('hide');

	});

});
