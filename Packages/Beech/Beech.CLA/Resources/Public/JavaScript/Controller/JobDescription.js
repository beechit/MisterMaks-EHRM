(function () {
	'use strict';
	App.BeechCLAJobDescriptionController = App.ModuleHandlerAjaxController.extend({
	});
	App.BeechCLAJobDescriptionIndexController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechCLAAdministrationJobDescriptionIndex
	});
	App.BeechCLAJobDescriptionNewController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechCLAAdministrationJobDescriptionNew
	});
	App.BeechCLAJobDescriptionShowController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechCLAAdministrationJobDescriptionShow
	});
	App.BeechCLAJobDescriptionEditController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechCLAAdministrationJobDescriptionEdit
	});
	App.BeechCLAJobDescriptionDeleteController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechCLAAdministrationJobDescriptionDelete
	});

}).call(this);