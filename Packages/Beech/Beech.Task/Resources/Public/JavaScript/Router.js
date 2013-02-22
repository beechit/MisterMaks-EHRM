(function() {
	'use strict';

	App.TaskModuleRoute = Ember.Route.extend({
		renderTemplate: function() {
			this.render('user_interface_breadcrumb_menu', { outlet: 'breadcrumbMenu', controller: 'breadcrumbMenu' });
			this.render('user_interface_user_menu', { outlet: 'userMenu' });
			this.render('user_interface_task_widget', { outlet: 'sidebar', controller: 'taskWidget' });
			this.render('task_module', {outlet: 'main'});
		},
		model: function() {
			return App.Task.find();
		}

	});

	App.TaskRoute = App.IndexRoute.extend({
		renderTemplate: function() {
			this.render('task', { outlet: 'main'});
		},
		model: function(params) {
			return App.Task.find(params.task_id);
		}
	});

	App.TaskNewRoute = Ember.Route.extend({
		renderTemplate: function() {
			this.render('user_interface_breadcrumb_menu', { outlet: 'breadcrumbMenu', controller: 'breadcrumbMenu' });
			this.render('user_interface_user_menu', { outlet: 'userMenu' });
			this.render('user_interface_task_widget', { outlet: 'sidebar', controller: 'taskWidget' });
			this.render('edit_task', { outlet: 'main', controller: 'new_task'});
		},

		model: function() {
			return person.createRecord();
		},

		exit: function() {
			var model = this.get('controller.model');
			if (!model.get('isSaving')) {
				model.deleteRecord();
			}
		}
	});

	App.TaskEditRoute = App.IndexRoute.extend({
		renderTemplate: function() {
			this.render('user_interface_breadcrumb_menu', { outlet: 'breadcrumbMenu', controller: 'breadcrumbMenu' });
			this.render('user_interface_user_menu', { outlet: 'userMenu' });
			this.render('user_interface_task_widget', { outlet: 'sidebar', controller: 'taskWidget' });
			this.render('edit_task', { outlet: 'main' });
		},
		model: function(params) {
			return App.Task.find(params.task_id);
		}
	});

}).call(this);