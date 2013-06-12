(function() {
	'use strict';

	App.TaskModuleRoute = App.ModuleRoute.extend({
		deserialize: function(params){
			return {params: App.ModuleHandler.prepareUrl(params)}
		},
		setupController: function(controller, model) {
			controller.loadUrl(model.params);
		}
	});
	App.BeechTaskTaskIndexRoute = App.TaskModuleRoute.extend();
	App.BeechTaskTaskNewRoute = App.TaskModuleRoute.extend();
	App.BeechTaskTaskShowRoute = App.TaskModuleRoute.extend();
	App.BeechTaskTaskEditRoute = App.TaskModuleRoute.extend();
	App.BeechTaskTaskCloseRoute = App.TaskModuleRoute.extend();

}).call(this);