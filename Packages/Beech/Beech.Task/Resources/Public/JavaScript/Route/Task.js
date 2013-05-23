(function() {
	'use strict';

	App.TaskModuleRoute = App.ModuleHandlerAjaxRoute.extend({
		setupController: function(controller, model) {
			var params = model.params;
			if(!params && model.id) {
				params = '?task='+model.id;
			}
			controller.loadUrl(params);
		}
	});
	App.BeechTaskTaskIndexRoute = App.TaskModuleRoute.extend();
	App.BeechTaskTaskNewRoute = App.TaskModuleRoute.extend();
	App.BeechTaskTaskShowRoute = App.TaskModuleRoute.extend();
	App.BeechTaskTaskEditRoute = App.TaskModuleRoute.extend();
	App.BeechTaskTaskCloseRoute = App.TaskModuleRoute.extend();

}).call(this);