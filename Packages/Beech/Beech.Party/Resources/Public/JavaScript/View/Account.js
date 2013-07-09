(function() {
	'use strict';
	App.BeechPartyAccountViewMixin = Ember.View.extend(App.AjaxModuleViewMixin, {
		didInsertElement: function() {/* override default */}
	});

	// Front
	App.BeechPartyAccountIndexView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.BeechPartyAccountIndex
	});
	App.BeechPartyAccountShowView = App.BeechPartyAccountViewMixin.extend();
	App.BeechPartyAccountNewView = App.BeechPartyAccountViewMixin.extend();
	App.BeechPartyAccountEditView = App.BeechPartyAccountViewMixin.extend();
	App.BeechPartyAccountDeleteView = App.BeechPartyAccountViewMixin.extend();

	// Administration
	App.BeechPartyAdministrationAccountIndexView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.BeechPartyAdministrationAccountIndex
	});
	App.BeechPartyAdministrationAccountShowView = App.BeechPartyAccountViewMixin.extend();
	App.BeechPartyAdministrationAccountNewView = App.BeechPartyAccountViewMixin.extend();
	App.BeechPartyAdministrationAccountEditView = App.BeechPartyAccountViewMixin.extend();
	App.BeechPartyAdministrationAccountDeleteView = App.BeechPartyAccountViewMixin.extend();

}).call(this);