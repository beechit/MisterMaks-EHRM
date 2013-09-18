(function() {
	'use strict';

	// Front
	App.BeechChartReportController = App.ModuleHandlerAjaxController.extend();
	App.BeechChartReportIndexController = App.BeechChartReportController.extend({
		url: MM.url.module.BeechChartGeneralAgeReportIndex
	});

}).call(this);