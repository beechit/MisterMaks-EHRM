(function() {
	'use strict';

	App.ApplicationView = Ember.View.extend();
	App.ApplicationView.reopen({
		classNames: ['full']
	});
	App.ApplicationController = Ember.Controller.extend();
	App.ApplicationController = App.ApplicationController.reopen({
		title: 'Mister Maks',

		init: function() {
			$('.datepicker').live('click', function() {
				$(this).datepicker({showOn:'focus'})
					.on('changeDate', function() {
						$(this).datepicker('hide')
					})
					.focus();
			});
		},

		ready: function() {
			$('[rel=tooltip]').tooltip();
			$('[rel=popover]').popover();
		}
	});
}).call(this);
