(function() {
	'use strict';
	App.ApplicationController = App.ApplicationController.reopen({
		title: 'Mister Maks',

		ready: function() {
			$('[rel=tooltip]').tooltip();
			$('[rel=popover]').popover();
		}
	});
}).call(this);