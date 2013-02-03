App.TasksFormable = Ember.Mixin.create({
	renderTemplate: function() {
		this.render('user_interface_breadcrumb_menu', { outlet: 'breadcrumbMenu', controller: 'breadcrumbMenu' });
		this.render('user_interface_user_menu', { outlet: 'userMenu' });
		this.render('user_interface_task_widget', { outlet: 'sidebar', controller: 'taskWidget' });
		this.render('module_task_new', { outlet: 'main'});
	},
	events: {
		cancel: function(task) {
			task.transaction.rollback();
			this.transitionTo('taskModule');
		},
		submit: function(task) {
			task.get('store').commit();
			if (task.didCreate) {
				this.transitionTo('taskModule');
			}
		}
	}
});
