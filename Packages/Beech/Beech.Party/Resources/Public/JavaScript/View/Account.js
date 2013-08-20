(function() {
	'use strict';

	// Front
	App.BeechPartyAccountView = Ember.View.extend(App.AjaxModuleViewMixin);
	App.BeechPartyAccountIndexView = App.BeechPartyAccountView.extend();
	App.BeechPartyAccountShowView = App.BeechPartyAccountView.extend();
	App.BeechPartyAccountNewView = App.BeechPartyAccountView.extend();
	App.BeechPartyAccountEditView = App.BeechPartyAccountView.extend();
	App.BeechPartyAccountDeleteView = App.BeechPartyAccountView.extend();

	// Administration
	App.BeechPartyAdministrationAccountView = App.BeechPartyAccountView.extend();;
	App.BeechPartyAdministrationAccountIndexView = App.BeechPartyAccountView.extend();;
	App.BeechPartyAdministrationAccountShowView = App.BeechPartyAccountView.extend();
	App.BeechPartyAdministrationAccountNewView = App.BeechPartyAccountView.extend();
	App.BeechPartyAdministrationAccountEditView = App.BeechPartyAccountView.extend();
	App.BeechPartyAdministrationAccountDeleteView = App.BeechPartyAccountView.extend();

}).call(this);