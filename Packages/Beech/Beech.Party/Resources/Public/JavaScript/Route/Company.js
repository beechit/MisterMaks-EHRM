(function() {
	'use strict';

	App.BeechPartyCompanyRoute = App.ModuleHandlerAjaxRoute.extend();

	// Front
	App.BeechPartyCompanyIndexRoute = App.BeechPartyCompanyRoute.extend();
	App.BeechPartyCompanyNewRoute = App.BeechPartyCompanyRoute.extend();
	App.BeechPartyCompanyEditRoute = App.BeechPartyCompanyRoute.extend();
	App.BeechPartyCompanyShowRoute = App.BeechPartyCompanyRoute.extend();
	App.BeechPartyCompanyDeleteRoute = App.BeechPartyCompanyRoute.extend();

	// Administration
	App.BeechPartyAdministrationCompanyRoute = App.BeechPartyCompanyRoute.extend();
	App.BeechPartyAdministrationCompanyIndexRoute = App.BeechPartyCompanyRoute.extend();
	App.BeechPartyAdministrationCompanyNewRoute = App.BeechPartyCompanyRoute.extend();
	App.BeechPartyAdministrationCompanyEditRoute = App.BeechPartyCompanyRoute.extend();
	App.BeechPartyAdministrationCompanyShowRoute = App.BeechPartyCompanyRoute.extend();
	App.BeechPartyAdministrationCompanyDeleteRoute = App.BeechPartyCompanyRoute.extend();


}).call(this);