(function( $ ) {
    $(function() {
        $('.color-field').wpColorPicker();
		$('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});
	});
	$("#image-url").on('keyup', function() {
		if( $(this).val() ) {
			$('#snow_flakecolor,#snow_round,#snow_shadow').addClass('item-disabled');
			$('#clear').show('100');
		}
		if($('#image-url').val() == ''){
			$('#snow_flakecolor,#snow_round,#snow_shadow').removeClass('item-disabled');
			$('#clear').hide('100');
		}
	});
	$("#clear").on('click', function() {
		document.getElementById("image-url").value = "";
		$('#snow_flakecolor,#snow_round,#snow_shadow').removeClass('item-disabled');
		$('#clear').hide('100');
	});
	$(".helpicon").hover(function () {
		var tooltipText = $(this).attr('title');
		$(this).append('<div class="tooltip"><p>' + tooltipText + '</p></div>');
	}, function () {
		$("div.tooltip").remove();
	});
	$('.helpicon').hover(function(e){
		$(this).attr('data-title', $(this).attr('title'));
		$(this).removeAttr('title');
	},function(e){
		$(this).attr('title', $(this).attr('data-title'));
	});
    $("input").on('keyup', function() {
		if( parseInt($(this).val()) >= 301 ) {
			$("#count-max").show();
		} else {
			$("#count-max").hide();
		}
    });
	$('.input_desktop').change(function () {$('#label_desktop').text(this.checked ? 'Activated' : 'Deactivated');}).change();
	$('.input_disabled').change(function () {$('#label_disabled').text(this.checked ? 'Activated' : 'Deactivated');}).change();
	$('.input_file').change(function () {$('#label_file').text(this.checked ? 'Activated' : 'Deactivated');}).change();
	$('.input_script').change(function () {$('#label_script').text(this.checked ? 'Activated' : 'Deactivated');}).change();
})( jQuery );