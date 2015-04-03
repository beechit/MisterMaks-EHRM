(function() {
	'use strict';

	// Front
	App.BeechDocumentDocumentRoute = App.ModuleHandlerAjaxRoute.extend();
	App.BeechDocumentDocumentIndexRoute = App.BeechDocumentDocumentRoute.extend();
	App.BeechDocumentDocumentNewRoute = App.BeechDocumentDocumentRoute.extend();
	App.BeechDocumentDocumentEditRoute = App.BeechDocumentDocumentRoute.extend();
	App.BeechDocumentDocumentShowRoute = App.BeechDocumentDocumentRoute.extend();
	App.BeechDocumentDocumentDeleteRoute = App.BeechDocumentDocumentRoute.extend();
	App.BeechDocumentDocumentChartRoute = App.BeechDocumentDocumentRoute.extend();

	// Administration
	App.BeechDocumentAdministrationDocumentRoute = App.BeechDocumentDocumentRoute.extend();
	App.BeechDocumentAdministrationDocumentIndexRoute = App.BeechDocumentDocumentRoute.extend();
	App.BeechDocumentAdministrationDocumentNewRoute = App.BeechDocumentDocumentRoute.extend();
	App.BeechDocumentAdministrationDocumentEditRoute = App.BeechDocumentDocumentRoute.extend();
	App.BeechDocumentAdministrationDocumentShowRoute = App.BeechDocumentDocumentRoute.extend();
	App.BeechDocumentAdministrationDocumentDeleteRoute = App.BeechDocumentDocumentRoute.extend();

}).call(this);