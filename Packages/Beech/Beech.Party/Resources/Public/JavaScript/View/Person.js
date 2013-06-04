(function() {
	'use strict';
	App.BeechPartyPersonViewMixin = Ember.View.extend(App.AjaxModuleViewMixin, {
		didInsertElement: function() {/* override default */}
	});

	// Front
	App.BeechPartyPersonIndexView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.BeechPartyPersonIndex
	});
	App.BeechPartyAdministrationPersonShowView = App.BeechPartyPersonViewMixin.extend();
	App.BeechPartyPersonNewView = App.BeechPartyPersonViewMixin.extend();
	App.BeechPartyPersonEditView = App.BeechPartyPersonViewMixin.extend();
	App.BeechPartyPersonDeleteView = App.BeechPartyPersonViewMixin.extend();

	// Administration
	App.BeechPartyAdministrationPersonIndexView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.BeechPartyAdministrationPersonIndex
	});
	App.BeechPartyAdministrationPersonShowView = App.BeechPartyPersonViewMixin.extend();
	App.BeechPartyAdministrationPersonNewView = App.BeechPartyPersonViewMixin.extend();
	App.BeechPartyAdministrationPersonEditView = App.BeechPartyPersonViewMixin.extend();
	App.BeechPartyAdministrationPersonDeleteView = App.BeechPartyPersonViewMixin.extend();

}).call(this);