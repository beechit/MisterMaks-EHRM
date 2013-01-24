(function() {
	'use strict';

	App.Todo = Ember.Object.extend({
		label: null,
		priority: null
	});
	App.Todo.reopenClass({
		find: function() {}
	});

	App.AllTodosView = App.AllTodosView.reopen();

	App.AllTodosController = Ember.ArrayController.create({
		content: [],
		init: function() {
			this.loadTasks();
		},
		loadTasks: function() {
			var _this = this;
			$.ajax({
				format: 'json',
				dataType: 'json',
				url: MM.configuration.restTaskUri,
				success: function(data) {
					if (data.result.status == 'success') {
						$.each(data.groups, function(priority, item) {
							if (!_this.groupExist(priority)) {
								_this.addGroup(item.label, priority);
							}
						});
						$.each(data.items, function(index, item) {
							_this.addTaskToGroup(item.label, item.priority);
						});
					} else {
						alert(result.success)
					}
				}
			});
		},
		groupExist: function(priority) {
			var obj = this.get('content').filterProperty('priority', priority);
			return (obj.length > 0) ? true : false;
		},
		addGroup: function(label, priority) {
			this.get('content').pushObject(Ember.Object.create({id: 'group-' + priority, priority: priority, label: label, items: []}));
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