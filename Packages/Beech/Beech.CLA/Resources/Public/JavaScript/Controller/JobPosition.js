(function () {
	'use strict';

	App.BeechCLAJobPositionController = App.ModuleHandlerAjaxController.extend();
	App.BeechCLAJobPositionIndexController = App.BeechCLAJobPositionController.extend({
		url: MM.url.module.BeechCLAJobPositionIndex
	});
	App.BeechCLAJobPositionNewController = App.BeechCLAJobPositionController.extend({
		url: MM.url.module.BeechCLAJobPositionNew
	});
	App.BeechCLAJobPositionShowController = App.BeechCLAJobPositionController.extend({
		url: MM.url.module.BeechCLAJobPositionShow
	});
	App.BeechCLAJobPositionEditController = App.BeechCLAJobPositionController.extend({
		url: MM.url.module.BeechCLAJobPositionEdit
	});
	App.BeechCLAJobPositionDeleteController = App.BeechCLAJobPositionController.extend({
		url: MM.url.module.BeechCLAJobPositionDelete
	});

}).call(this);