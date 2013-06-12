(function() {
	'use strict';

	// default resource view needs to be empty Index is loaded direct after this one
	// http://emberjs.com/guides/routing/defining-your-routes/#toc_resources
	App.BeechTaskTaskView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: ''
	});

}).call(this);