(function() {
	'use strict';

	App.BeechPartyAdministrationPersonRoute = App.ModuleRoute.extend({
		deserialize: function(params){
			return {params: App.ModuleHandler.prepareUrl(params)}
		},
		setupController: function(controller, model) {
			controller.loadUrl(model.params);
		}
	});

	App.BeechPartyAdministrationPersonNewRoute = App.BeechPartyAdministrationPersonRoute.extend();

	App.BeechPartyAdministrationPersonEditRoute = App.BeechPartyAdministrationPersonRoute.extend();

	App.BeechPartyAdministrationPersonShowRoute = App.BeechPartyAdministrationPersonRoute.extend();

	App.BeechPartyAdministrationPersonDeleteRoute = App.BeechPartyAdministrationPersonRoute.extend();


}).call(this);