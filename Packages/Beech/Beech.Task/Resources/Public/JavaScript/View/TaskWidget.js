(function() {
	'use strict';

	App.BeechTaskTaskWidgetView = Ember.View.extend({
		elementId: 'task-widget',
		templateName: 'beech_task_user_interface_task_widget',
		go: function() {
console.log('go');
		//	this.rerender();
		}.observes('tasks.@each.priority')

	});

}).call(this);