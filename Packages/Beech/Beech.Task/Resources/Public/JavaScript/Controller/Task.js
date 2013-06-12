(function() {
	'use strict';
	App._TaskModuleController = Ember.Controller.extend({
		url: '',
		loadUrl: function(params) {
			App.ModuleHandler.loadUrl(this.get('url') + '?'+ params.substr(1));
		}
	});

	App.BeechTaskTaskIndexController = App._TaskModuleController.extend({
		url: MM.url.module.taskModule
	});
	App.BeechTaskTaskNewController = App._TaskModuleController.extend({
		url: MM.url.module.taskModuleNew
	});
	App.BeechTaskTaskEditController = App._TaskModuleController.extend({
		url: MM.url.module.taskModuleEdit
	});
	App.BeechTaskTaskShowController = App._TaskModuleController.extend({
		url: MM.url.module.taskModuleShow
	});
	App.BeechTaskTaskCloseController = App._TaskModuleController.extend({
		url: MM.url.module.taskModuleClose
	});

}).call(this);