(function() {
	'use strict';

	App.TaskWidgetView = Ember.CollectionView.extend({
	});

	App.TaskWidgetController = Ember.ArrayController.extend({
		content: [],
		tasks: [],
		init: function() {
			this.set('priorities', App.Priority.all());
			this.set('content', App.Task.find());
		}
	});

}).call(this);