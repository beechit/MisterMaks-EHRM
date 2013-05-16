(function() {
	'use strict';

	App.BeechTaskTaskWidgetController = Ember.ArrayController.extend({
		content: [],
		init: function() {
			this.set('content', App.BeechTaskDomainModelPriority.find());
		}
	});

}).call(this);