(function() {
	'use strict';

	// Front
	App.BeechPartyAccountRoute = App.ModuleHandlerAjaxRoute.extend();
	App.BeechPartyAccountIndexRoute = App.BeechPartyAccountRoute.extend();
	App.BeechPartyAccountNewRoute = App.BeechPartyAccountRoute.extend();
	App.BeechPartyAccountEditRoute = App.BeechPartyAccountRoute.extend();
	App.BeechPartyAccountShowRoute = App.BeechPartyAccountRoute.extend();
	App.BeechPartyAccountDeleteRoute = App.BeechPartyAccountRoute.extend();

	// Administration
	App.BeechPartyAdministrationAccountRoute = App.BeechPartyAccountRoute.extend();
	App.BeechPartyAdministrationAccountIndexRoute = App.BeechPartyAccountRoute.extend();
	App.BeechPartyAdministrationAccountNewRoute = App.BeechPartyAccountRoute.extend();
	App.BeechPartyAdministrationAccountEditRoute = App.BeechPartyAccountRoute.extend();
	App.BeechPartyAdministrationAccountShowRoute = App.BeechPartyAccountRoute.extend();
	App.BeechPartyAdministrationAccountDeleteRoute = App.BeechPartyAccountRoute.extend();

}).call(this);