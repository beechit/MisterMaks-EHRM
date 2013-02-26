(function() {
	'use strict';

	App.TaskWidgetController = Ember.ArrayController.extend({
		content: [],
		init: function() {
			this.set('content', App.BeechTaskDomainModelPriority.find());
		}
	});

}).call(this);