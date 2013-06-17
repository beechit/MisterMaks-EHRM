(function () {
	'use strict';
	App.BeechCLAContractArticleController = App.ModuleHandlerAjaxController.extend({
	});
	App.BeechCLAContractArticleIndexController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechCLAAdministrationContractArticleIndex
	});
	App.BeechCLAContractArticleNewController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechCLAAdministrationContractArticleNew
	});
	App.BeechCLAContractArticleShowController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechCLAAdministrationContractArticleShow
	});
	App.BeechCLAContractArticleEditController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechCLAAdministrationContractArticleEdit
	});
	App.BeechCLAContractArticleDeleteController = App.ModuleHandlerAjaxController.extend({
		url: MM.url.module.BeechCLAAdministrationContractArticleDelete
	});

}).call(this);