(function() {
	'use strict';
	App.BeechPartyAdministrationCompanyViewMixin = Ember.View.extend(App.AjaxModuleViewMixin, {
		didInsertElement: function() {/* override default */}
	});

	App.BeechPartyAdministrationCompanyIndexView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.BeechPartyAdministrationCompanyIndex
	});
	App.BeechPartyAdministrationCompanyShowView = App.BeechPartyAdministrationCompanyViewMixin.extend();
	App.BeechPartyAdministrationCompanyNewView = App.BeechPartyAdministrationCompanyViewMixin.extend();
	App.BeechPartyAdministrationCompanyEditView = App.BeechPartyAdministrationCompanyViewMixin.extend();
	App.BeechPartyAdministrationCompanyDeleteView = App.BeechPartyAdministrationCompanyViewMixin.extend();

}).call(this);