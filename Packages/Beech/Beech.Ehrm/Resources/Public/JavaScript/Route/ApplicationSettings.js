(function() {
	'use strict';

	App.BeechEhrmApplicationSettingsRoute = App.ModuleHandlerAjaxRoute.extend();
	App.BeechEhrmApplicationSettingsIndexRoute = App.BeechEhrmApplicationSettingsRoute.extend();
	App.BeechEhrmApplicationSettingsSetupWizardRoute = App.BeechEhrmApplicationSettingsRoute.extend();

	// Administration
	App.BeechEhrmAdministrationApplicationSettingsRoute = App.ModuleHandlerAjaxRoute.extend();
	App.BeechEhrmAdministrationApplicationSettingsIndexRoute = App.BeechEhrmAdministrationApplicationSettingsRoute.extend();

}).call(this);