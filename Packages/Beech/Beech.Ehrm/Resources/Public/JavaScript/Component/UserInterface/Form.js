(function() {
	'use strict';

	$(function () {
		$(document).on('change', '.articles-section .controls.input input, .articles-section .controls.input select', function() {
			var identifier = $(this).attr('id');
			if(identifier) {
				$('#' + identifier.replace('_','\\.') + '_text').html($(this).val());
			}
		});

		$(document).on('hover', '.help', function() {
			$(this).tooltip({placement: 'top', trigger: 'hover', delay: { show: 100, hide: 500 }}).tooltip('show')
		});
			// block help tooltip clicking
		$(document).on('click', '.help', function() {
			return false;
		});
	});

}).call(this);