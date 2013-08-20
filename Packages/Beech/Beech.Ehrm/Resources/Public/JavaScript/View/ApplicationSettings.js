(function() {
	'use strict';

	// Administration
	App.BeechEhrmAdministrationApplicationSettingsView = Ember.View.extend(App.AjaxModuleViewMixin);
	App.BeechEhrmAdministrationApplicationSettingsIndexView = App.BeechEhrmAdministrationApplicationSettingsView.extend();
	App.BeechEhrmAdministrationApplicationSettingsSetupWizardView = App.BeechEhrmAdministrationApplicationSettingsView.extend();

}).call(this);