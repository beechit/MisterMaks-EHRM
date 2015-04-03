(function() {
	'use strict';

	App.BeechCLAJobPositionView = Ember.View.extend(App.AjaxModuleViewMixin);
	App.BeechCLAJobPositionIndexView = App.BeechCLAJobPositionView.extend();
	App.BeechCLAJobPositionNewView = App.BeechCLAJobPositionView.extend();
	App.BeechCLAJobPositionEditView = App.BeechCLAJobPositionView.extend();
	App.BeechCLAJobPositionShowView = App.BeechCLAJobPositionView.extend();
	App.BeechCLAJobPositionDeleteView = App.BeechCLAJobPositionView.extend();

	}).call(this);