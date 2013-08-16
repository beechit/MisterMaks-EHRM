(function() {
	'use strict';
	$('.documentPhoto').on('focus', function() {
		if ($(this).val() != '') {
			$('.submit-documentPhoto').show()
		}
	})
}).call(this);