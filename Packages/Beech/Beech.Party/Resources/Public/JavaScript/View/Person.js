(function() {
	'use strict';

	// Front
	App.BeechPartyPersonView = Ember.View.extend(App.AjaxModuleViewMixin);
	App.BeechPartyPersonIndexView =  App.BeechPartyPersonView.extend();
	App.BeechPartyPersonShowView = App.BeechPartyPersonView.extend();
	App.BeechPartyPersonNewView = App.BeechPartyPersonView.extend();
	App.BeechPartyPersonRefreshNewView = App.BeechPartyPersonView.extend();
	App.BeechPartyPersonEditView = App.BeechPartyPersonView.extend();
	App.BeechPartyPersonDeleteView = App.BeechPartyPersonView.extend();

	// Administration
	App.BeechPartyAdministrationPersonView = App.BeechPartyPersonView.extend();
	App.BeechPartyAdministrationPersonIndexView = App.BeechPartyPersonView.extend();
	App.BeechPartyAdministrationPersonShowView = App.BeechPartyPersonView.extend();
	App.BeechPartyAdministrationPersonNewView = App.BeechPartyPersonView.extend();
	App.BeechPartyAdministrationPersonRefreshNewView = App.BeechPartyPersonView.extend();
	App.BeechPartyAdministrationPersonEditView = App.BeechPartyPersonView.extend();
	App.BeechPartyAdministrationPersonDeleteView = App.BeechPartyPersonView.extend();

}).call(this);