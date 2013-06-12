(function() {
	'use strict';
	App.TaskModuleController = Ember.Controller.extend({
		url: '',
		loadUrl: function(params) {
			App.ModuleHandler.loadUrl(this.get('url') + '?'+ params.substr(1));
		}
	});

	App.BeechTaskTaskModuleIndexController = App.TaskModuleController.extend({
		url: MM.url.module.taskModule
	});
	App.BeechTaskTaskModuleNewController = App.TaskModuleController.extend({
		url: MM.url.module.taskModuleNew
	});
	App.BeechTaskTaskModuleEditController = App.TaskModuleController.extend({
		url: MM.url.module.taskModuleEdit
	});
	App.BeechTaskTaskModuleShowController = App.TaskModuleController.extend({
		url: MM.url.module.taskModuleShow
	});
	App.BeechTaskTaskModuleCloseController = App.TaskModuleController.extend({
		url: MM.url.module.taskModuleClose
	});

}).call(this);