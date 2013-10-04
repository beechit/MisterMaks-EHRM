(function() {
	'use strict';

	// Front
	App.BeechChartGeneralController = App.ModuleHandlerAjaxController.extend();
	App.BeechChartGeneralIndexController = App.BeechChartGeneralController.extend({
		url: MM.url.module.BeechChartGeneralAgeReportIndex
	});
	App.BeechChartGeneralAgeIndexController = App.BeechChartGeneralController.extend({
		url: MM.url.module.BeechChartGeneralAgeReportIndex
	});

}).call(this);