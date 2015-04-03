(function() {
	'use strict';
	App.BeechTaskTaskController = App.ModuleHandlerAjaxController.extend();
	App.BeechTaskTaskIndexController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechTaskTaskIndex
	});
	App.BeechTaskTaskNewController = App.BeechTaskTaskIndexController.extend({
		url: MM.url.module.BeechTaskTaskNew
	});
	App.BeechTaskTaskEditController = App.BeechTaskTaskIndexController.extend({
		url: MM.url.module.BeechTaskTaskEdit
	});
	App.BeechTaskTaskShowController = App.BeechTaskTaskIndexController.extend({
		url: MM.url.module.BeechTaskTaskShow
	});
	App.BeechTaskTaskCloseController = App.BeechTaskTaskIndexController.extend({
		url: MM.url.module.BeechTaskTaskClose
	});

}).call(this);