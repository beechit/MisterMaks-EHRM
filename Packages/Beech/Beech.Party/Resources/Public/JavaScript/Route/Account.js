(function() {
	'use strict';

	App.BeechPartyAccountRoute = App.ModuleRoute.extend({
		deserialize: function(params){
			return {params: App.ModuleHandler.prepareUrl(params)}
		},
		setupController: function(controller, model) {
			controller.loadUrl(model.params);
		}
	});

	// Front
	App.BeechPartyAccountNewRoute = App.BeechPartyAccountRoute.extend();

	App.BeechPartyAccountEditRoute = App.BeechPartyAccountRoute.extend();

	App.BeechPartyAccountShowRoute = App.BeechPartyAccountRoute.extend();

	App.BeechPartyAccountDeleteRoute = App.BeechPartyAccountRoute.extend();

	// Administration
	App.BeechPartyAdministrationAccountNewRoute = App.BeechPartyAccountRoute.extend();

	App.BeechPartyAdministrationAccountEditRoute = App.BeechPartyAccountRoute.extend();

	App.BeechPartyAdministrationAccountShowRoute = App.BeechPartyAccountRoute.extend();

	App.BeechPartyAdministrationAccountDeleteRoute = App.BeechPartyAccountRoute.extend();


}).call(this);