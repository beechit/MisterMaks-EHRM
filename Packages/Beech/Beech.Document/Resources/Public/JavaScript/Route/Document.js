(function() {
	'use strict';

	App.BeechDocumentDocumentRoute = App.ModuleRoute.extend({
		deserialize: function(params){
			return {params: App.ModuleHandler.prepareUrl(params)}
		},
		setupController: function(controller, model) {
			controller.loadUrl(model.params);
		}
	});

	// Front
	App.BeechDocumentDocumentNewRoute = App.BeechDocumentDocumentRoute.extend();

	App.BeechDocumentDocumentEditRoute = App.BeechDocumentDocumentRoute.extend();

	App.BeechDocumentDocumentShowRoute = App.BeechDocumentDocumentRoute.extend();

	App.BeechDocumentDocumentDeleteRoute = App.BeechDocumentDocumentRoute.extend();

	// Administration
	App.BeechDocumentAdministrationDocumentNewRoute = App.BeechDocumentDocumentRoute.extend();

	App.BeechDocumentAdministrationDocumentEditRoute = App.BeechDocumentDocumentRoute.extend();

	App.BeechDocumentAdministrationDocumentShowRoute = App.BeechDocumentDocumentRoute.extend();

	App.BeechDocumentAdministrationDocumentDeleteRoute = App.BeechDocumentDocumentRoute.extend();


}).call(this);