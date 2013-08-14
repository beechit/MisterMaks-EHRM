(function() {
	'use strict';
	App.BeechEhrmApplicationSettingsView = Ember.View.extend(App.AjaxModuleViewMixin, {
		didInsertElement: function() {/* override default */}
	});

	// Administration
	App.BeechEhrmAdministrationApplicationSettingsIndexView = App.BeechEhrmApplicationSettingsView.extend();
	App.BeechEhrmAdministrationApplicationSettingsSetupWizardView = App.BeechEhrmApplicationSettingsView.extend();

}).call(this);