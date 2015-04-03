(function() {
	'use strict';

	// Front
	App.BeechPartyPersonRoute = App.ModuleHandlerAjaxRoute.extend();
	App.BeechPartyPersonIndexRoute = App.BeechPartyPersonRoute.extend();
	App.BeechPartyPersonNewRoute = App.BeechPartyPersonRoute.extend();
	App.BeechPartyPersonEditRoute = App.BeechPartyPersonRoute.extend();
	App.BeechPartyPersonShowRoute = App.BeechPartyPersonRoute.extend();
	App.BeechPartyPersonDeleteRoute = App.BeechPartyPersonRoute.extend();

	// Administration
	App.BeechPartyAdministrationPersonRoute = App.BeechPartyPersonRoute.extend();
	App.BeechPartyAdministrationPersonIndexRoute = App.BeechPartyPersonRoute.extend();
	App.BeechPartyAdministrationPersonNewRoute = App.BeechPartyPersonRoute.extend();
	App.BeechPartyAdministrationPersonEditRoute = App.BeechPartyPersonRoute.extend();
	App.BeechPartyAdministrationPersonShowRoute = App.BeechPartyPersonRoute.extend();
	App.BeechPartyAdministrationPersonDeleteRoute = App.BeechPartyPersonRoute.extend();


}).call(this);