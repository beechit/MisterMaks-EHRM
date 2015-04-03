(function() {
	'use strict';

	App.BeechEhrmApplicationSettingsView = Ember.View.extend(App.AjaxModuleViewMixin);
	App.BeechEhrmApplicationSettingsIndexView = App.BeechEhrmApplicationSettingsView.extend();
	App.BeechEhrmApplicationSettingsSetupWizardView = App.BeechEhrmApplicationSettingsView.extend();

	// Administration
	App.BeechEhrmAdministrationApplicationSettingsView = Ember.View.extend(App.AjaxModuleViewMixin);
	App.BeechEhrmAdministrationApplicationSettingsIndexView = App.BeechEhrmAdministrationApplicationSettingsView.extend();

}).call(this);