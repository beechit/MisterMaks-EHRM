(function() {
	'use strict';

	App.BeechTaskTaskIndexController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.taskModule
	});
	App.BeechTaskTaskNewController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.taskModuleNew
	});
	App.BeechTaskTaskEditController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.taskModuleEdit
	});
	App.BeechTaskTaskShowController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.taskModuleShow
	});
	App.BeechTaskTaskCloseController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.taskModuleClose
	});

}).call(this);