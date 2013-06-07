(function() {
	'use strict';

	App.BeechPartyCompanyRoute = App.ModuleRoute.extend({
		deserialize: function(params){
			return {params: App.ModuleHandler.prepareUrl(params)}
		},
		setupController: function(controller, model) {
			controller.loadUrl(model.params);
		}
	});

	// Front
	App.BeechPartyCompanyNewRoute = App.BeechPartyCompanyRoute.extend();

	App.BeechPartyCompanyEditRoute = App.BeechPartyCompanyRoute.extend();

	App.BeechPartyCompanyShowRoute = App.BeechPartyCompanyRoute.extend();

	App.BeechPartyCompanyDeleteRoute = App.BeechPartyCompanyRoute.extend();

	// Administration
	App.BeechPartyAdministrationCompanyNewRoute = App.BeechPartyCompanyRoute.extend();

	App.BeechPartyAdministrationCompanyEditRoute = App.BeechPartyCompanyRoute.extend();

	App.BeechPartyAdministrationCompanyShowRoute = App.BeechPartyCompanyRoute.extend();

	App.BeechPartyAdministrationCompanyDeleteRoute = App.BeechPartyCompanyRoute.extend();


}).call(this);