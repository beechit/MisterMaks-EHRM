(function() {
	'use strict';

	App.TaskModuleIndexRoute = App.IndexRoute.extend({
		renderTemplate: function() {
			this.render('user_interface_breadcrumb_menu', { outlet: 'breadcrumbMenu', controller: 'breadcrumbMenu' });
			this.render('user_interface_user_menu', { outlet: 'userMenu' });
			this.render('user_interface_task_widget', { outlet: 'sidebar', controller: 'taskWidget' });
			this.render('module_task', {outlet: 'main'});
		},
		model: function() {
			return App.Task.find();
		}
	});

	App.TaskRoute = App.IndexRoute.extend({
		renderTemplate: function() {
			this.render('module_task_show', { outlet: 'main'});
		},
		model: function(params) {
			return App.Task.find(params.task_id);
		}
	});

	App.TaskModuleNewRoute = App.IndexRoute.extend(App.TasksFormable, {
		model: function() {
			return App.Task.createRecord();
		}
	});

	App.TaskEditRoute = App.IndexRoute.extend(App.TasksFormable, {
		model: function(params) {
			return App.Task.find(params.task_id);
		}
	});

}).call(this);