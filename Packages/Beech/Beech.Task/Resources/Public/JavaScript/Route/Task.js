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
	App.BeechTaskTaskModuleIndexRoute = App.TaskModuleRoute.extend();
	App.BeechTaskTaskModuleNewRoute = App.TaskModuleRoute.extend();
	App.BeechTaskTaskModuleShowRoute = App.TaskModuleRoute.extend();
	App.BeechTaskTaskModuleEditRoute = App.TaskModuleRoute.extend();
	App.BeechTaskTaskModuleCloseRoute = App.TaskModuleRoute.extend();

}).call(this);