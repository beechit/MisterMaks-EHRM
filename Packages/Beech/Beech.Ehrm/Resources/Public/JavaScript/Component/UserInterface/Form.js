(function() {
	'use strict';

	$(function () {
		$(document).on('change', '.articles-section .controls.input input, .articles-section .controls.input select', function() {
			var identifier = $(this).attr('id').replace('_','\\.');
			$('#' + identifier + '_text').html($(this).val());
		});

		$(document).on('hover', '.help', function() {
			$(this).tooltip('show');
		});

	});

}).call(this);