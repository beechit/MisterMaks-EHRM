(function() {
	'use strict';

	// default resource view needs to be empty Index is loaded direct after this one
	// http://emberjs.com/guides/routing/defining-your-routes/#toc_resources
	App.BeechTaskTaskView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: ''
	});

//	App.BeechTaskTaskModuleIndexView = Ember.View.extend({
//		templateName: 'beech_task_domain_model_task/index'
//	});
//	App.BeechTaskDomainModelTaskIndexView = Ember.View.extend({
//		templateName: 'beech_task_domain_model_task/show'
//	});

}).call(this);