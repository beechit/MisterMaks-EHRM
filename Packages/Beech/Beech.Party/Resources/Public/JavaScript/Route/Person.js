(function() {
	'use strict';

	App.BeechPartyPersonRoute = App.ModuleRoute.extend({
		deserialize: function(params){
			return {params: App.ModuleHandler.prepareUrl(params)}
		},
		setupController: function(controller, model) {
			controller.loadUrl(model.params);
		}
	});

	// Front
	App.BeechPartyPersonNewRoute = App.BeechPartyPersonRoute.extend();

	App.BeechPartyPersonEditRoute = App.BeechPartyPersonRoute.extend();

	App.BeechPartyPersonShowRoute = App.BeechPartyPersonRoute.extend();

	App.BeechPartyPersonDeleteRoute = App.BeechPartyPersonRoute.extend();

	// Administration
	App.BeechPartyAdministrationPersonNewRoute = App.BeechPartyPersonRoute.extend();

	App.BeechPartyAdministrationPersonEditRoute = App.BeechPartyPersonRoute.extend();

	App.BeechPartyAdministrationPersonShowRoute = App.BeechPartyPersonRoute.extend();

	App.BeechPartyAdministrationPersonDeleteRoute = App.BeechPartyPersonRoute.extend();


}).call(this);