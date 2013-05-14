(function() {
	'use strict';
	App.BeechPartyAdministrationPersonViewMixin = Ember.View.extend(App.AjaxModuleViewMixin, {
		didInsertElement: function() {/* override default */}
	});

	App.BeechPartyAdministrationPersonIndexView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.BeechPartyAdministrationPersonIndex
	});
	App.BeechPartyAdministrationPersonShowView = App.BeechPartyAdministrationPersonViewMixin.extend();
	App.BeechPartyAdministrationPersonNewView = App.BeechPartyAdministrationPersonViewMixin.extend();
	App.BeechPartyAdministrationPersonEditView = App.BeechPartyAdministrationPersonViewMixin.extend();
	App.BeechPartyAdministrationPersonDeleteView = App.BeechPartyAdministrationPersonViewMixin.extend();

}).call(this);