(function () {
	'use strict';
	App.BeechCLAJobDescriptionController = App.ModuleHandlerAjaxController.extend();
	App.BeechCLAJobDescriptionIndexController = App.BeechCLAJobDescriptionController.extend({
		url: MM.url.module.BeechCLAAdministrationJobDescriptionIndex
	});
	App.BeechCLAJobDescriptionNewController = App.BeechCLAJobDescriptionController.extend({
		url: MM.url.module.BeechCLAAdministrationJobDescriptionNew
	});
	App.BeechCLAJobDescriptionShowController = App.BeechCLAJobDescriptionController.extend({
		url: MM.url.module.BeechCLAAdministrationJobDescriptionShow
	});
	App.BeechCLAJobDescriptionEditController = App.BeechCLAJobDescriptionController.extend({
		url: MM.url.module.BeechCLAAdministrationJobDescriptionEdit
	});
	App.BeechCLAJobDescriptionDeleteController = App.BeechCLAJobDescriptionController.extend({
		url: MM.url.module.BeechCLAAdministrationJobDescriptionDelete
	});

}).call(this);