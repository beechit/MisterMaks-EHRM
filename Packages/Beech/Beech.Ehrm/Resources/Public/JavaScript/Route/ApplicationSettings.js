(function() {
	'use strict';

	App.BeechEhrmApplicationSettingsRoute = App.ModuleRoute.extend({
		deserialize: function(params){
			return {params: App.ModuleHandler.prepareUrl(params)}
		},
		setupController: function(controller, model) {
			controller.loadUrl(model.params);
		}
	});

	// Administration
	App.BeechEhrmAdministrationApplicationSettingsIndexRoute = App.BeechEhrmApplicationSettingsRoute.extend();
	App.BeechEhrmAdministrationApplicationSettingsSetupWizardRoute = App.BeechEhrmApplicationSettingsRoute.extend();


}).call(this);