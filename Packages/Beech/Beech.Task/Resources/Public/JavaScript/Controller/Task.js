(function() {
	'use strict';

	App.TaskController = Ember.ObjectController.extend({

		destroy: function() {
			if (!confirm('Are you sure?')) {
				return;
			}

			this.get('content').deleteRecord();
			App.store.commit();
			this.get('target').transitionTo('taskModule');
		}

	});

	App.EditTaskController = Ember.ObjectController.extend({

		save: function() {
			App.store.commit();
			this.redirectToModel();
		},

		redirectToModel: function() {
			var router = this.get('target');
			var model = this.get('model');
			router.transitionTo('task', model);
		}

	});

	App.NewTaskController = App.EditTaskController.extend();

}).call(this);