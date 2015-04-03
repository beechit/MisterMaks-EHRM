(function() {
	'use strict';
	$('.documentPhoto').on('change', function() {
		if ($(this).val() != '') {
			$('.submit-documentPhoto').show()
		}
	})
}).call(this);