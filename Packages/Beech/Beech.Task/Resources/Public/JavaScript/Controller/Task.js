(function() {
	'use strict';

	App.BeechTaskTaskIndexController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechTaskTaskIndex
	});
	App.BeechTaskTaskNewController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechTaskTaskNew
	});
	App.BeechTaskTaskEditController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechTaskTaskEdit
	});
	App.BeechTaskTaskShowController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechTaskTaskShow
	});
	App.BeechTaskTaskCloseController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechTaskTaskClose
	});

}).call(this);