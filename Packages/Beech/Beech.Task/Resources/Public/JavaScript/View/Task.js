(function() {
	'use strict';

	App.BeechTaskTaskModuleIndexView = Ember.View.extend({
		templateName: 'beech_task_domain_model_task/index',
		remove: function(task) {
			task.deleteRecord();
			App.store.commit();
		}
	});
	App.BeechTaskDomainModelTaskIndexView = Ember.View.extend({
		templateName: 'beech_task_domain_model_task/show'
	});

}).call(this);