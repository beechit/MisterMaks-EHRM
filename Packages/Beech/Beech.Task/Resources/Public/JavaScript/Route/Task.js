(function() {
	'use strict';

	App.BeechTaskTaskModuleIndexController = Ember.ArrayController.extend();
	App.BeechTaskTaskModuleIndexRoute = App.ModuleRoute.extend({
		model: function() {
			return App.BeechTaskDomainModelTask.find();
		}
	});

	App.BeechTaskTaskModuleNewRoute = Ember.Route.extend(App.ModelFormableMixin, {
		redirectToRouteName: 'BeechTaskTaskModule.index',
		formTemplateName: 'BeechTaskDomainModelTask/form',
		model: function() {
			return App.BeechTaskDomainModelTask.createRecord();
		}
	});

	App.BeechTaskDomainModelTaskEditRoute = Ember.Route.extend(App.ModelFormableMixin, {
		redirectToRouteName: 'BeechTaskTaskModule.index',
		formTemplateName: 'BeechTaskDomainModelTask/form',
		model: function() {
			return this.modelFor('BeechTaskDomainModelTask');
		}
	});

	App.BeechTaskTaskModuleIndexView = Ember.View.extend({
		templateName: 'beech_task_domain_model_task/index'
	});
	App.BeechTaskDomainModelTaskIndexView = Ember.View.extend({
		templateName: 'beech_task_domain_model_task/show'
	});

}).call(this);