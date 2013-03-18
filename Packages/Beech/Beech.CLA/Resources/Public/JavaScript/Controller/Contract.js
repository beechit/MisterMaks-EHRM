(function() {
	'use strict';
	App.ContractModuleController = Ember.Controller.extend({
		url: '',
		loadUrl: function(params) {
			App.ModuleHandler.loadUrl(this.get('url') + params);
		}
	});

	App.AdministrationContractModuleNewController = App.ContractModuleController.extend({
		url: MM.url.module.contractModuleNew
	});
	App.AdministrationContractModuleStartController = App.ContractModuleController.extend({
		url: MM.url.module.contractModuleStart
	});
	App.AdministrationContractModuleShowController = App.ContractModuleController.extend({
		url: MM.url.module.contractModuleShow
	});

}).call(this);
