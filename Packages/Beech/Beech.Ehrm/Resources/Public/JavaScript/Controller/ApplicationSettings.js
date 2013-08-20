(function() {
	'use strict';

	// Administration
	App.BeechEhrmAdministrationApplicationSettingsController = App.ModuleHandlerAjaxController.extend();
	App.BeechEhrmAdministrationApplicationSettingsIndexController = App.BeechEhrmAdministrationApplicationSettingsController.extend({
		url: MM.url.module.BeechEhrmAdministrationApplicationSettingsIndex
	});
	App.BeechEhrmAdministrationApplicationSettingsSetupWizardController = App.BeechEhrmAdministrationApplicationSettingsController.extend({
		url: MM.url.module.BeechEhrmAdministrationApplicationSettingsSetupWizard
	});

}).call(this);