(function() {
	'use strict';

	App.Todo = Ember.Object.extend({
		label: null,
		priority: null,
		find: function() {}
	});

	App.TaskWidgetView = Ember.CollectionView.extend({
		didInsertElement: function() {
		}
	});

	App.TaskWidgetController = Ember.ArrayController.extend({
		content: [],
		init: function() {
			this.loadTasks();
		},
		loadTasks: function() {
			var that = this;
			$.ajax({
				format: 'json',
				dataType: 'json',
				url: MM.configuration.restTaskUri,
				success: function(data) {
					if (data.result.status == 'success') {
						$.each(data.groups, function(priority, item) {
							if (!that.groupExist(priority)) {
								that.addGroup(item.label, priority);
							}
						});
						$.each(data.items, function(index, item) {
							that.addTaskToGroup(item.label, item.priority);
						});
					}
				}
			});
		},
		groupExist: function(priority) {
			return this.get('content').filterProperty('priority', priority).length > 0;
		},
		addGroup: function(label, priority) {
			this.pushObject(
				Ember.Object.create({id: 'group-' + priority, priority: priority, label: label, items: []})
			);
		},
		addTaskToGroup: function(label, index) {
			var properties = {label: label, priority: index};
			this.get('content').get(index).get('items').pushObject(App.Todo.create(properties));
		}
	});

	Handlebars.registerHelper('showId', function() {
		return new Handlebars.SafeString(
			this.id
		);
	});

}).call(this);