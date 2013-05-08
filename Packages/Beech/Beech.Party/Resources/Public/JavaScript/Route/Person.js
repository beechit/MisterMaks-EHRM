(function() {
	'use strict';

	App.PersonModuleRoute = App.ModuleRoute.extend({
		deserialize: function(params){
			return {params: App.ModuleHandler.prepareUrl(params)}
		},
		setupController: function(controller, model) {
			controller.loadUrl(model.params);
		}
	});

	App.AdministrationPersonModuleNewRoute = App.PersonModuleRoute.extend();

	App.AdministrationPersonModuleEditRoute = App.PersonModuleRoute.extend();

	App.AdministrationPersonModuleShowRoute = App.PersonModuleRoute.extend();

	App.AdministrationPersonModuleDeleteRoute = App.PersonModuleRoute.extend();


}).call(this);