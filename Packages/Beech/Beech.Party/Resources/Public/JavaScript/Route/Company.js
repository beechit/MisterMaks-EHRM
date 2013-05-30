(function() {
	'use strict';

	App.BeechPartyAdministrationCompanyRoute = App.ModuleRoute.extend({
		deserialize: function(params){
			return {params: App.ModuleHandler.prepareUrl(params)}
		},
		setupController: function(controller, model) {
			controller.loadUrl(model.params);
		}
	});

	App.BeechPartyAdministrationCompanyNewRoute = App.BeechPartyAdministrationCompanyRoute.extend();

	App.BeechPartyAdministrationCompanyEditRoute = App.BeechPartyAdministrationCompanyRoute.extend();

	App.BeechPartyAdministrationCompanyShowRoute = App.BeechPartyAdministrationCompanyRoute.extend();

	App.BeechPartyAdministrationCompanyDeleteRoute = App.BeechPartyAdministrationCompanyRoute.extend();


}).call(this);