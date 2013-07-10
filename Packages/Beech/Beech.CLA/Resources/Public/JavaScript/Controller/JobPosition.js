(function () {
	'use strict';
	App.BeechCLAJobPositionController = App.ModuleHandlerAjaxController.extend({
	});
	App.BeechCLAJobPositionIndexController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechCLAJobPositionIndex
	});
	App.BeechCLAJobPositionNewController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechCLAJobPositionNew
	});
	App.BeechCLAJobPositionShowController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechCLAJobPositionShow
	});
	App.BeechCLAJobPositionEditController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechCLAJobPositionEdit
	});
	App.BeechCLAJobPositionDeleteController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechCLAJobPositionDelete
	});

}).call(this);