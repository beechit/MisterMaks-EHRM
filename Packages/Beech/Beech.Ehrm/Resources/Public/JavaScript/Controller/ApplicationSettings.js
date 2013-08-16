(function() {
	'use strict';
	App.BeechEhrmApplicationSettingsController = Ember.Controller.extend({
		url: '',
		loadUrl: function(params) {
			App.ModuleHandler.loadUrl(this.get('url') + params);
		}
	});

	// Administration
	App.BeechEhrmAdministrationApplicationSettingsIndexController = App.BeechEhrmApplicationSettingsController.extend({
		url: MM.url.module.BeechEhrmAdministrationApplicationSettingsIndex
	});
	App.BeechEhrmAdministrationApplicationSettingsSetupWizardController = App.BeechEhrmApplicationSettingsController.extend({
		url: MM.url.module.BeechEhrmAdministrationApplicationSettingsSetupWizard
	});

}).call(this);