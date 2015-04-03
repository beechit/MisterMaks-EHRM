(function() {
	'use strict';

	App.BeechEhrmApplicationSettingsController = App.ModuleHandlerAjaxController.extend();
	App.BeechEhrmApplicationSettingsIndexController = App.BeechEhrmApplicationSettingsController.extend();
	App.BeechEhrmApplicationSettingsSetupWizardController = App.BeechEhrmApplicationSettingsController.extend({
		url: MM.url.module.BeechEhrmAdministrationApplicationSettingsSetupWizard
	});

	// Administration
	App.BeechEhrmAdministrationApplicationSettingsController = App.ModuleHandlerAjaxController.extend();
	App.BeechEhrmAdministrationApplicationSettingsIndexController = App.BeechEhrmAdministrationApplicationSettingsController.extend({
		url: MM.url.module.BeechEhrmAdministrationApplicationSettingsIndex
	});

}).call(this);