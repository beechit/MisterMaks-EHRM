(function() {
	'use strict';

	$(function () {
		$(document).on('change keyup blur', '.articles-section input, .articles-section select', function() {
			var value = "";
			if($(this).parents('span.timepicker[id!=""]').length) {
				var timepickerspan = $(this).parents('span.timepicker[id!=""]');
				value = parseInt($('select:first',timepickerspan).val())+"."+$('select:last',timepickerspan).val()
				$('#' + timepickerspan.attr('id').replace('_','\\.') + '_text').html(value);

			} else if($(this).attr('id')) {
				var identifier = $(this).attr('id');
					if($(this).is('select')) {
					value = $('option:selected', this).text();
				} else {
					value = $(this).val();
				}
				$('#' + identifier.replace('_','\\.') + '_text').html(value);
			} else if($(this).parents('ul.inputs-list[id!=""]').length) {
				var values = [];
				$('li label', $(this).parents('ul.inputs-list[id!=""]')).each(function() {
					if($(this).find('input:checked').length) {
						values.push($.trim($(this).text()));
					}
				});
				$('#' + $(this).parents('ul.inputs-list[id!=""]').attr('id').replace('_','\\.') + '_text').html(values.join(', '));
			}

		});

		$(document).on('hover', '.help', function() {
			$(this).tooltip({placement: 'top', trigger: 'hover', delay: { show: 30, hide: 500 }}).tooltip('show')
		});
			// block help tooltip clicking
		$(document).on('click', '.help', function() {
			return false;
		});
	});

}).call(this);