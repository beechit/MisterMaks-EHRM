(function() {
	'use strict';

	App.BeechCLAJobDescriptionView = Ember.View.extend(App.AjaxModuleViewMixin);
	App.BeechCLAJobDescriptionIndexView = App.BeechCLAJobDescriptionView.extend();
	App.BeechCLAJobDescriptionNewView = App.BeechCLAJobDescriptionView.extend();
	App.BeechCLAJobDescriptionEditView = App.BeechCLAJobDescriptionView.extend();
	App.BeechCLAJobDescriptionShowView = App.BeechCLAJobDescriptionView.extend();
	App.BeechCLAJobDescriptionDeleteView = App.BeechCLAJobDescriptionView.extend();

}).call(this);