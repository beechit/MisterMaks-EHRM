(function() {
	'use strict';

	App.Todo = Ember.Object.extend();
	App.Todo.reopenClass({
		find: function() {
			return [
				{
					'label': 'high',
					'items': [
						{label: 'test'}
					]
				},
				{
					'label': 'normal',
					'items': [
						{label: 'test'}
					]
				}
			];
		}
	});

	App.AllTodosView = App.AllTodosView.reopen({
		classNames: ['well']
	});

}).call(this);