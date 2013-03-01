(function() {
	'use strict';

	App.BeechTaskTaskModuleIndexController = Ember.ArrayController.extend();
	App.BeechTaskTaskModuleIndexRoute = Ember.Route.extend({
		renderTemplate: function() {
			this.render('beech_task_user_interface_task_widget', { outlet: 'sidebar', controller: 'taskWidget' });
			this._super.apply(this, arguments);
		},
		model: function() {
			return App.BeechTaskDomainModelTask.find();
		}
	});

	App.TasksFormable = Ember.Mixin.create({
		renderTemplate: function() {
			return this.render('BeechTaskDomainModelTask/form', { outlet: 'main' });
		},
		events: {
			cancel: function(BeechTaskDomainModelTask) {
				BeechTaskDomainModelTask.transaction.rollback();
				return this.transitionTo('BeechTaskTaskModule.index');
			},
			submit: function(content) {
				content.get('store').commit();
				return this.transitionTo('BeechTaskTaskModule.index');
			}
		}
	});

	App.BeechTaskTaskModuleNewRoute = Ember.Route.extend(App.TasksFormable, {
		model: function() {
			return App.BeechTaskDomainModelTask.createRecord();
		}
	});

	App.BeechTaskDomainModelTaskEditRoute = Ember.Route.extend(App.TasksFormable, {
		model: function() {
			return this.modelFor('BeechTaskDomainModelTask');
		}
	});

}).call(this);