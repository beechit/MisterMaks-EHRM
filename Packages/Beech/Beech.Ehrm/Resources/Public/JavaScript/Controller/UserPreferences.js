(function() {
	'use strict';

	App.BeechEhrmUserPreferencesController = App.ModuleHandlerAjaxController.extend();
	App.BeechEhrmUserPreferencesIndexController = App.BeechEhrmUserPreferencesController.extend({
		url: MM.url.module.userSettings
	});

}).call(this);