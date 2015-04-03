(function() {
	'use strict';

	// Front
	App.BeechPartyCompanyView = Ember.View.extend(App.AjaxModuleViewMixin);
	App.BeechPartyCompanyIndexView = Ember.View.extend(App.AjaxModuleViewMixin);
	App.BeechPartyCompanyShowView = App.BeechPartyCompanyView.extend();
	App.BeechPartyCompanyNewView = App.BeechPartyCompanyView.extend();
	App.BeechPartyCompanyEditView = App.BeechPartyCompanyView.extend();
	App.BeechPartyCompanyDeleteView = App.BeechPartyCompanyView.extend();

	// Administration
	App.BeechPartyAdministrationCompanyView = Ember.View.extend(App.AjaxModuleViewMixin);
	App.BeechPartyAdministrationCompanyIndexView = Ember.View.extend(App.AjaxModuleViewMixin);
	App.BeechPartyAdministrationCompanyShowView = App.BeechPartyCompanyView.extend();
	App.BeechPartyAdministrationCompanyNewView = App.BeechPartyCompanyView.extend();
	App.BeechPartyAdministrationCompanyEditView = App.BeechPartyCompanyView.extend();
	App.BeechPartyAdministrationCompanyDeleteView = App.BeechPartyCompanyView.extend();

}).call(this);