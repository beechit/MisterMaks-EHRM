(function() {
	'use strict';

	App.datePickerSettings = {
		showOn: 'focus',
		minView: 3
	};

	App.dateTimePickerSettings = {
		minuteStep: 15,
		showOn: 'focus',
		autoclose: true
	};

	/** ember datetimepicker view **/
	App.DateTimePicker = Em.View.extend({
		attributeBindings: ['value', 'hrValue'],
		templateName: 'userInterface/dateTimePicker',
		value: '',
		hrValue: '',
		timeOnly: false,
		dateOnly: true,

		/**
		 * The date format, combination of p, P, h, hh, i, ii, s, ss, d, dd, m, mm, M, MM, yy, yyyy.
		 *
		 * p : meridian in lower case ('am' or 'pm') - according to locale file
		 * P : meridian in upper case ('AM' or 'PM') - according to locale file
		 * s : seconds without leading zeros
		 * ss : seconds, 2 digits with leading zeros
		 * i : minutes without leading zeros
		 * ii : minutes, 2 digits with leading zeros
		 * h : hour without leading zeros - 24-hour format
		 * hh : hour, 2 digits with leading zeros - 24-hour format
		 * H : hour without leading zeros - 12-hour format
		 * HH : hour, 2 digits with leading zeros - 12-hour format
		 * d : day of the month without leading zeros
		 * dd : day of the month, 2 digits with leading zeros
		 * m : numeric representation of month without leading zeros
		 * mm : numeric representation of the month, 2 digits with leading zeros
		 * M : short textual representation of a month, three letters
		 * MM : full textual representation of a month, such as January or March
		 * yy : two digit representation of a year
		 * yyyy : full numeric representation of a year, 4 digits
		 */
		format: 'dd-mm-yyyy',

		/**
		 * The increment used to build the hour view. A preset is created for each minuteStep minutes.
		 */
		minuteStep: 5,

		/**
		 * @param {string} date
		 * @param {string} format
		 * @return {string}
		 */
		formatDate: function(date, format) {
			return $.fn.datetimepicker.DPGlobal.formatDate(
				date,
				$.fn.datetimepicker.DPGlobal.parseFormat(format, 'standard'),
				'en',
				'standard'
			);
		},

		/**
		 * @param {string} date
		 * @param {string} format
		 * @return {string}
		 */
		parseDate: function(date, format) {
			return $.fn.datetimepicker.DPGlobal.parseDate(
				date,
				$.fn.datetimepicker.DPGlobal.parseFormat(format, 'standard'),
				'en',
				'standard'
			);
		},

		/**
		 * @return {string}
		 */
		updateHrValue: function() {
			var value = '';
			if (this.get('value')) {
				value = this.formatDate(new Date(this.get('value')), this.get('format'));
			}
			this.set('hrValue', value);
		}.observes('value'),

		/**
		 * @return {void}
		 */
		didInsertElement: function() {
			// update hr value
			this.updateHrValue();

			var that = this,
				$datetimepicker = this.$('.datetimepicker-hrvalue'),
				viewSettings = this.calculateViewSettings(),
				todayBtn = 'linked';

			if (viewSettings.maxView < 2) {
				this.set('timeOnly', true);
				todayBtn = false;
			}

			$datetimepicker.attr('readonly',true).datetimepicker({
				showOn: 'focus',
				format: this.get('format'),
				minuteStep: this.get('minuteStep'),
				todayHighlight: true,
				todayBtn: todayBtn,
				minView: viewSettings.minView,
				maxView: viewSettings.maxView,
				startView: viewSettings.startView,
				weekStart: 1
			}).on('changeDate',function(event) {
					that.set('value', that.formatDate(new Date(event.date), 'yyyy-mm-dd'));
				});

			this.$('.icon-calendar').parent().on('click', function() {
				$datetimepicker.trigger('focus');
			});

			this.$('.icon-remove').parent().on('click', function() {
				$datetimepicker.val('');
				$datetimepicker.datetimepicker('update', null);
				that.set('value', '');
			});

			if (this.get('value')) {
				$datetimepicker.datetimepicker('update', this.get('hrValue'));
			}
//			$datetimepicker.on('change', function() {
//				that.set('value', that.formatDate(that.parseDate($datetimepicker.val(), that.get('format')), 'yyyy-mm-dd'));
//			});
		},

		/**
		 * 0 or 'hour' for the hour view
		 * 1 or 'day' for the day view
		 * 2 or 'month' for month view (the default)
		 * 3 or 'year' for the 12-month overview
		 * 4 or 'decade' for the 10-year overview. Useful for date-of-birth datetimepickers.
		 *
		 * @return {object}
		 */
		calculateViewSettings: function() {
			var format = this.get('format').toLowerCase(),
				minView = 0,
				maxView = 4,
				startView = 2;
			if (format.indexOf('y') === -1) {
				maxView = 3;
				if (format.indexOf('m') === -1) {
					maxView = 2;
					if (format.indexOf('d') === -1) {
						maxView = 1;
						if (format.indexOf('h') === -1) {
							maxView = 0;
						}
					}
				}
			}

			if (format.indexOf('i') === -1 && format.indexOf('s') === -1) {
				minView = 1;
				if (format.indexOf('h') === -1) {
					minView = 2;
					if (format.indexOf('d') === -1) {
						minView = 3;
						if (format.indexOf('m') === -1) {
							minView = 4;
						}
					}
				}
			}

			if (startView < minView) {
				startView = minView;
			}

			if (startView > maxView) {
				startView = maxView;
			}

			return {minView: minView, maxView: maxView, startView: startView};
		}
	});

}).call(this);
