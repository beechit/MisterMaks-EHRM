(function() {
	'use strict';

	App.PersonAdministrationController = Ember.ObjectController.extend({
	});

	App.PersonAdministrationShowController = App.PersonAdministrationController.extend({
		init: function() {
			console.log(this.get('content'));
		}
	});

	App.PersonAdministrationEditController = App.PersonAdministrationController.extend({
	});

}).call(this);