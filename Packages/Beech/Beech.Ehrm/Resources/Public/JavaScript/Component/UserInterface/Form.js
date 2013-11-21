(function() {
	'use strict';

	/**
	 * TODO: fix this method
	 * @param fieldIdentifier
	 */
	function adjustUnitsLabels(fieldIdentifier) {
		var time = getUnitOfTIme(fieldIdentifier, true);
		$('#'+fieldIdentifier+'_unit').find('option').each(function(index, element) {
			if ($(this).val() != 'W') {
				$(this).text( moment.duration(time.amount, mapISO8601ToMomentsFormat($(this).val())).humanize(false).replace(/[0-9]/g, ''))
			}

		})
		$('#'+fieldIdentifier+'_unit').trigger("chosen:updated")
	}

	/**
	 * http://momentjs.com/docs/#/durations/creating/
	 * @param shorthand
	 */
	function mapISO8601ToMomentsFormat(shorthand) {
		if (shorthand !=  'M') {
			return shorthand.toLowerCase();
		} else {
			return shorthand;
		}
	}

	/**
	 * TODO: Extend it. This works only for dates in format: 'DD-MM-YYYY'
	 * @param unit
	 * @returns {*}
	 */
	function calculateContractDuration(unit) {
		var startDate = moment($('#contractCreator-article-10000-values_startDate').val(), 'DD-MM-YYYY');
		var endDate = moment($('#contractCreator-article-10000-values_endDate').val(), 'DD-MM-YYYY');
		return endDate.diff(startDate, unit);
	}

	/**
	 * Calculate max probation periods based on duration of contract
	 * If unit is defined then return value of max probation for this unit
	 * @param contractDurationInYears
	 * @param unit
	 * @returns {*}
	 */
	function getMaxProbation(contractDurationInYears, unit) {
		var maxProbationPeriod = {d: 30, w: 4, M: 1};
		if (contractDurationInYears >= 2) {
			maxProbationPeriod= {d: 60, w: 8, M: 2};
		}
		if (unit != undefined) {
			return maxProbationPeriod[unit];
		}
		return maxProbationPeriod;
	}

	/**
	 *
	 */
	function unitsOfTimeValidate(fieldIdentifier) {
		var contractDurationInYears = calculateContractDuration('years');
		var maxProbationPeriod = getMaxProbation(contractDurationInYears);
		var time = getUnitOfTIme(fieldIdentifier, true);
		return time.amount <= maxProbationPeriod[time.unit];
	}

	function setMaxProbation(fieldIdentifier, unit) {
		var maxAmount = getMaxProbation(calculateContractDuration('years'),unit);
		$('#'+fieldIdentifier+'_amount').val(maxAmount);
		return maxAmount;
	}

	/**
	 *
	 * @param fieldIdentifier
	 * @param formatted
	 * @returns {{amount: Number, unit: *}}
	 */
	function getUnitOfTIme(fieldIdentifier, formatted) {
		if (formatted != undefined) {
			return {
				amount: parseInt($('#'+fieldIdentifier+'_amount').val()),
				unit: mapISO8601ToMomentsFormat($('#'+fieldIdentifier+'_unit').val())
			}
		} else {
			return {
				amount: $('#'+fieldIdentifier+'_amount').val(),
				unit: $('#'+fieldIdentifier+'_unit').val()
			}
		}
	}

	function timeAsText(time) {
		var text = '';
		var rest = 0;
		var divideUnit = 1;
		switch(time.unit) {
			case 'd':
				divideUnit = 30;
				break;
			case 'w':
				divideUnit = 4;
				break;
			default:
				divideUnit = 1;
				break;
		}
		if (Math.floor(time.amount / divideUnit) > 0) {
			text = moment.duration(Math.floor(time.amount / divideUnit) * divideUnit, time.unit).humanize(false);
		}
		rest = time.amount % divideUnit;
		if (rest > 0) {
			if (text != "") {
				text += ', '
			}
			text += moment.duration(rest, time.unit).humanize(false);
		}
		if (text == '') {
			$('strong[amount="0"]').show();
			$('strong[amount="positive"]').hide();
		} else {
			$('strong[amount="positive"]').show();
			$('strong[amount="0"]').hide();
		}

		return text;
	}

	function dayOfWeek(number) {
		return moment().day(number).format('dddd');
	}

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
				} else if ($(this).hasClass('iso8601')) {
					var time = getUnitOfTIme($(this).attr('id'), true);
					value = timeAsText(time);
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

		$(document).on('change', '.unitOfTime', function() {
			var fieldIdentifier = $(this).parent().find('.iso8601').attr('id');
			var time = getUnitOfTIme(fieldIdentifier, true);


			var validationResults = unitsOfTimeValidate(fieldIdentifier);
			if (!validationResults) {
				time.amount = setMaxProbation(fieldIdentifier, time.unit);
			}
			var iso8601 = 'P'+time.amount+time.unit.toUpperCase();
			$(this).parent().find('.iso8601').val(iso8601).trigger('change');
			// this method still should be fix
			// adjustUnitsLabels(fieldIdentifier)
		})

		$(document).on('hover', '.help', function() {
			$(this).tooltip({placement: 'top', trigger: 'hover', delay: { show: 30, hide: 500 }}).tooltip('show')
		});
			// block help tooltip clicking
		$(document).on('click', '.help', function() {
			return false;
		});

		$(document).on('change', '.articles-section .workDaySelect', function() {
			var identifier = $(this).attr('id');
			var value;
			if ($(this).val() != null) {
				var valuesArray = []
				$.each($(this).val(), function(index, element) {
					valuesArray.push(dayOfWeek(element))
				})
				value = valuesArray.join();
			}
			$('#' + identifier.replace('_','\\.') + '_text').html(value);
		});
	});

	$.fn.extend({
		convertISO8601ToText: function(options) {
			return this.each(function(index) {
				var valueISO8601 = $(this).text();
				var text = valueISO8601.replace(/^P([0-9]+)([a-zA-Z]+)/g, function($1, $2, $3){ return timeAsText({amount: parseInt($2), unit: mapISO8601ToMomentsFormat($3)})});
				$(this).text(text);
			})
		}
	});

	$.fn.extend({
		workDaySelect: function(options) {
			return this.each(function(index) {
				$('#' + $(this).attr('id')).find('option').each(function(index, element) {
					if ($(element).val() != '') {
						$(element).text( dayOfWeek($(element).val()) );
					}
				})
				$('#' + $(this).attr('id')).trigger("chosen:updated")
			})
		}
	});
}).call(this);