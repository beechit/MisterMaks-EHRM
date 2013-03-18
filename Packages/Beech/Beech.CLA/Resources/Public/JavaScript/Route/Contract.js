(function() {
	'use strict';

	App.ContractModuleRoute = App.ModuleRoute.extend({
		deserialize: function(params){
			return {params: App.ModuleHandler.prepareUrl(params)}
		},
		setupController: function(controller, model) {
			controller.loadUrl(model.params);
		}
	});

	App.AdministrationContractModuleNewRoute = App.ContractModuleRoute.extend();

	App.AdministrationContractModuleStartRoute = App.ContractModuleRoute.extend();

	App.AdministrationContractModuleShowRoute = App.ContractModuleRoute.extend();

}).call(this);