(function() {
	'use strict';
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