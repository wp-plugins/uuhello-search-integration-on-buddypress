jQuery(document).ready(function() {
	var ssp_activePicker = null;
	var ssp_farbtastic = jQuery.farbtastic('#ssp_farbtastic', ssp_colorPicked);

	jQuery(document).mousedown(function(){
		jQuery('#ssp_farbtastic').hide();
		ssp_activePicker = null;
	});

	jQuery('.ssp_colorpicker').bind('click', ssp_popUpFarbtastic);
	jQuery('.ssp_colorpicker_text').bind('change', ssp_color_changeAfterInput);

	function ssp_popUpFarbtastic(event) {
		jQuery(this).prev('input:first').focus();

		var color = new RGBColor(jQuery(this).css('background-color'));
		ssp_farbtastic.setColor(color.toHex());
		jQuery('#ssp_farbtastic').css({ left: (event.pageX+20)+'px', top: (event.pageY-180)+'px' });
		jQuery('#ssp_farbtastic').show();
		ssp_activePicker = jQuery(this);
	}

	function ssp_colorPicked(event) {
		if (ssp_activePicker != null) {
			ssp_activePicker.css("background", ssp_farbtastic.color);
			ssp_activePicker.prev('input:first').val(ssp_farbtastic.color);
			ssp_activePicker.prev('input:first').focus();
		}
	}

	function ssp_color_changeAfterInput(event) {
	
		var color = new RGBColor(document.getElementById(this.name).value);
		
		jQuery(this).next('input:first').focus().css('background-color', color.toHex() );

	}
});


