(function() {
	'use strict';
	App.ContractModuleViewMixin = Ember.View.extend(App.AjaxModuleViewMixin, {
		didInsertElement: function() {/* override default */}
	});
	App.AdministrationContractModuleView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.contractModule
	});
	App.AdministrationContractModuleNewView = App.ContractModuleViewMixin.extend();
	App.AdministrationContractModuleShowView = App.ContractModuleViewMixin.extend();
	App.AdministrationContractModuleStartView = App.ContractModuleViewMixin.extend();
}).call(this);