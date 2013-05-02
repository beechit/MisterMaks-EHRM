(function() {
	'use strict';
	App.ContractModuleViewMixin = Ember.View.extend(App.AjaxModuleViewMixin, {
		didInsertElement: function() {/* override default */}
	});
	App.AdministrationContractModuleView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.contractModule
	});

	App.AdministrationContractModuleShowView = App.ContractModuleViewMixin.extend();
	App.AdministrationContractModuleStartView = App.ContractModuleViewMixin.extend({
		didInsertElement: function() {
			$('#modal-body-only')
				.find('.modal-body').html('');
			this.$().prependTo('.modal-body')
			$('#modal-body-only')
				.addClass('modal-large')
				.modal('show');
		}
	});
	App.AdministrationContractModuleNewView = App.AdministrationContractModuleStartView;

}).call(this);