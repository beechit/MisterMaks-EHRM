(function() {
	'use strict';

	$('.datepicker').live('click', function() {
		var format = ($(this).attr('format') != undefined) ? $(this).attr('format') : 'dd-mm-yyyy';
		$(this).datepicker({showOn: 'focus', format: format})
			.on('changeDate', function() {
				$(this).datepicker('hide')
			})
			.focus();
	});

	$('.datepickerIcon').live('click', function() {
		$(this).prev('.datepicker').trigger('click');
	});

}).call(this);