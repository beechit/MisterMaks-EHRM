(function() {
	'use strict';
	// size = flag size + spacing
	var default_size = {
		w: 20,
		h: 15
	};

	function calcPos(letter, size) {
		if (letter !== undefined)
			return -(letter.toLowerCase().charCodeAt(0) - 97) * size;
	}

	$.fn.setFlagPosition = function (iso, size) {
		size || (size = default_size);

		return $(this).css('background-position',
			[calcPos(iso[1], size.w), 'px ', calcPos(iso[0], size.h), 'px'].join(''));
	};

	$.fn.extend({
		countrySelect: function() {
			return this.each(function(input_field) {
				var $this;
				$this = $(this);
				// on load:
				$this.on('change', function() {
					$(this).parent().find('.country i').setFlagPosition($(this).val());
				});
				// set default value to NL
				$this.val('NL').change();
			});
		}
	});
	$('.countrySelect').countrySelect();
}).call(this);
