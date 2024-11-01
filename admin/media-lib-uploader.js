jQuery(document).ready(function($){
	var mediaUploader;

	$('#upload-button').click(function(e) {
		e.preventDefault();

		if (mediaUploader) {
			mediaUploader.open();
			return;
		}

		mediaUploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose Image',
			button: {
			text: 'Choose'
			}, multiple: false
		});

		mediaUploader.on('select', function() {
			attachment = mediaUploader.state().get('selection').first().toJSON();
			$('#image-url').val(attachment.url);
			$('#snow_flakecolor,#snow_round,#snow_shadow').addClass('item-disabled');
			$('#clear').show('100');
		});

		mediaUploader.open();
	});

});