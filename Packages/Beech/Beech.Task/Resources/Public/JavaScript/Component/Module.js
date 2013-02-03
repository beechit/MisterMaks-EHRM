(function() {
	'use strict';

	App.TaskModuleController = Ember.ArrayController.extend({
		newTask: function() {
			var task = App.Task.createRecord([{"description":"test", "priority": App.Priority.find(1)}]);
			task.isNew();
		},
		deleteTask: function(task) {
			task.isDeleted();
		}
	});

	App.TasksFormView = Em.View.extend({
		tagName: 'form'
	});
}).call(this);