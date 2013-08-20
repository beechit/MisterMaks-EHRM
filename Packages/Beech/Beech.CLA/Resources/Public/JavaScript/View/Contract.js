(function() {
	'use strict';
	App.BeechCLAAdministrationContractView = Ember.View.extend(App.AjaxModuleViewMixin);
	App.BeechCLAAdministrationContractIndexView = App.BeechCLAAdministrationContractView.extend();
	App.BeechCLAAdministrationContractRefreshView = App.BeechCLAAdministrationContractView.extend();

	App.BeechCLAAdministrationContractShowView = App.BeechCLAAdministrationContractView.extend();
	App.BeechCLAAdministrationContractStartView = App.BeechCLAAdministrationContractView.extend({
		didInsertElement: function() {
			$('#modal-body-only')
				.find('.modal-body').html('');
			this.$().prependTo('.modal-body')
			$('#modal-body-only')
				.addClass('modal-large')
				.modal('show');
		}
	});
	App.BeechCLAAdministrationContractNewView = App.BeechCLAAdministrationContractStartView;

}).call(this);