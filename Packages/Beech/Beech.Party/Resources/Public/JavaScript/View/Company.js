(function() {
	'use strict';
	App.BeechPartyCompanyViewMixin = Ember.View.extend(App.AjaxModuleViewMixin, {
		didInsertElement: function() {/* override default */}
	});

	// Front
	App.BeechPartyCompanyIndexView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.BeechPartyCompanyIndex
	});
	App.BeechPartyCompanyShowView = App.BeechPartyCompanyViewMixin.extend();
	App.BeechPartyCompanyNewView = App.BeechPartyCompanyViewMixin.extend();
	App.BeechPartyCompanyEditView = App.BeechPartyCompanyViewMixin.extend();
	App.BeechPartyCompanyDeleteView = App.BeechPartyCompanyViewMixin.extend();

	// Administration
	App.BeechPartyAdministrationCompanyIndexView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.BeechPartyAdministrationCompanyIndex
	});
	App.BeechPartyAdministrationCompanyShowView = App.BeechPartyCompanyViewMixin.extend();
	App.BeechPartyAdministrationCompanyNewView = App.BeechPartyCompanyViewMixin.extend();
	App.BeechPartyAdministrationCompanyEditView = App.BeechPartyCompanyViewMixin.extend();
	App.BeechPartyAdministrationCompanyDeleteView = App.BeechPartyCompanyViewMixin.extend();

}).call(this);