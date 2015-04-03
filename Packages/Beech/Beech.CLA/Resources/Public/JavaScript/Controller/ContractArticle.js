(function () {
	'use strict';

	App.BeechCLAContractArticleController = App.ModuleHandlerAjaxController.extend();
	App.BeechCLAContractArticleIndexController = App.BeechCLAContractArticleController.extend({
		url: MM.url.module.BeechCLAAdministrationContractArticleIndex
	});
	App.BeechCLAContractArticleNewController = App.BeechCLAContractArticleController.extend({
		url: MM.url.module.BeechCLAAdministrationContractArticleNew
	});
	App.BeechCLAContractArticleShowController = App.BeechCLAContractArticleController.extend({
		url: MM.url.module.BeechCLAAdministrationContractArticleShow
	});
	App.BeechCLAContractArticleEditController = App.BeechCLAContractArticleController.extend({
		url: MM.url.module.BeechCLAAdministrationContractArticleEdit
	});
	App.BeechCLAContractArticleDeleteController = App.BeechCLAContractArticleController.extend({
		url: MM.url.module.BeechCLAAdministrationContractArticleDelete
	});

}).call(this);