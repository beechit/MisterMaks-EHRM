(function() {
	'use strict';
	$('.profilePhoto').on('focus', function() {
		if ($(this).val() != '') {
			$('.submit-profilePhoto').show()
		}
	})
}).call(this);