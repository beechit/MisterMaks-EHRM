(function() {
	'use strict';
	App.BeechCLAAdministrationContractView = Ember.View.extend(App.AjaxModuleViewMixin);
	App.BeechCLAAdministrationContractIndexView = App.BeechCLAAdministrationContractView.extend();

	App.BeechCLAAdministrationContractShowView = App.BeechCLAAdministrationContractView.extend();
	App.BeechCLAAdministrationContractStartView = App.BeechCLAAdministrationContractView.extend({
		didInsertElement: function() {
			$('#modal')
				.find('.modal-content').html('');
			this.$().prependTo('.modal-content')
			$('#modal')
				.addClass('modal-large')
				.modal('show');
		}
	});
	App.BeechCLAAdministrationContractNewView = App.BeechCLAAdministrationContractStartView;

}).call(this);