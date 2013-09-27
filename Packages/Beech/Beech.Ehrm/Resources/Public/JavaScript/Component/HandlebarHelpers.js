(function() {
	'use strict';

	/**
	 * Date handelbars helper
	 *
	 * {{date theDate 'DD-MM-YYYY'}}
	 */
	Ember.Handlebars.registerBoundHelper('date', function(date, format) {
		return moment(date).format(format);
	});

	/**
	 * Render the absence of a person/employee for a certain date
	 *
	 * {{absenceOfEmployee absences employee date}}
	 */
	Ember.Handlebars.registerBoundHelper('absenceOfEmployee', function(absences, employee, date) {

		if (!absences || !employee) {
			return new Handlebars.SafeString('<span class="loading">..</span>');
		}

		var $return = [], $date = moment(date);
		absences.forEach(function(absence) {

			if (absence.get('person') == employee) {

				// startDate and endDate know
				if (absence.get('endDate') && absence.get('startDate')) {
					if ($date.isSame(absence.get('startDate'), 'day')
						|| $date.isSame(absence.get('endDate'), 'day')
						|| ($date.isAfter(absence.get('startDate'), 'day') && $date.isBefore(absence.get('endDate'), 'day'))
						) {
						$return.push('<span class="'+absence.get('absenceType')+'"></span>')
					}

					// startDate and estimatedRecoveryDate know
				} else if (absence.get('estimatedRecoveryDate') && absence.get('startDate')) {
					if ($date.isSame(absence.get('startDate'), 'day')
						|| $date.isSame(absence.get('estimatedRecoveryDate'), 'day')
						|| ($date.isAfter(absence.get('startDate'), 'day') && $date.isBefore(absence.get('estimatedRecoveryDate'), 'day'))
						) {
						$return.push('<span class="estimated '+absence.get('absenceType')+'"></span>')
					}

					// no endDate and startDate is same day as date
				} else if (!absence.get('endDate') && absence.get('startDate')) {
					if ($date.isSame(absence.get('startDate'), 'day')) {
						$return.push('<span class="unknow-end '+absence.get('absenceType')+'"></span>')
					}
				}
			}
		});

		return new Handlebars.SafeString($return.join(''));
	});

}).call(this);