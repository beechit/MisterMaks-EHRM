(function() {
	'use strict';

	App.BeechTaskTaskModuleRoute = App.IndexRoute.extend({
		model: function(params) {
			return App.BeechTaskDomainModelTask.find();
		}
	});

}).call(this);