(function() {
	'use strict';

	App.BeechCLAAdministrationContractController = App.ModuleHandlerAjaxController.extend();
	App.BeechCLAAdministrationContractIndexController = App.BeechCLAAdministrationContractController.extend({
		url: MM.url.module.BeechCLAAdministrationContractIndex
	});
	App.BeechCLAAdministrationContractNewController = App.BeechCLAAdministrationContractController.extend({
		url: MM.url.module.BeechCLAAdministrationContractNew
	});
	App.BeechCLAAdministrationContractStartController = App.BeechCLAAdministrationContractController.extend({
		url: MM.url.module.BeechCLAAdministrationContractStart
	});
	App.BeechCLAAdministrationContractShowController = App.BeechCLAAdministrationContractController.extend({
		url: MM.url.module.BeechCLAAdministrationContractShow
	});
}).call(this);
