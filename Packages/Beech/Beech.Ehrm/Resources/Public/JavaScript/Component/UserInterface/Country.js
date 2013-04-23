(function() {
	'use strict';
	// size = flag size + spacing
	var default_size = {
		w: 20,
		h: 15
	};

	function calcPos(letter, size) {
		return -(letter.toLowerCase().charCodeAt(0) - 97) * size;
	}

	$.fn.setFlagPosition = function (iso, size) {
		size || (size = default_size);

		return $(this).css('background-position',
			[calcPos(iso[1], size.w), 'px ', calcPos(iso[0], size.h), 'px'].join(''));
	};

	$(function () {
		// on load:
		$('.countrySelect').on('change', function() {
			$(this).parent().find('.country i').setFlagPosition($(this).val());
		});
			// set default value to NL
		$('.countrySelect').val('NL').change();
	});

}).call(this);