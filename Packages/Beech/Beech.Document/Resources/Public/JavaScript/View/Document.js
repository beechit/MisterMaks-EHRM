(function() {
	'use strict';

	// Front
	App.BeechDocumentDocumentView = Ember.View.extend(App.AjaxModuleViewMixin);
	App.BeechDocumentDocumentIndexView = App.BeechDocumentDocumentView.extend();
	App.BeechDocumentDocumentShowView = App.BeechDocumentDocumentView.extend();
	App.BeechDocumentDocumentNewView = App.BeechDocumentDocumentView.extend();
	App.BeechDocumentDocumentEditView = App.BeechDocumentDocumentView.extend();
	App.BeechDocumentDocumentDeleteView = App.BeechDocumentDocumentView.extend();
	App.BeechDocumentDocumentChartView = App.BeechDocumentDocumentView.extend();

	// Administration
	App.BeechDocumentAdministrationDocumentView = App.BeechDocumentDocumentView.extend();
	App.BeechDocumentAdministrationDocumentIndexView = App.BeechDocumentDocumentView.extend();
	App.BeechDocumentAdministrationDocumentShowView = App.BeechDocumentDocumentView.extend();
	App.BeechDocumentAdministrationDocumentNewView = App.BeechDocumentDocumentView.extend();
	App.BeechDocumentAdministrationDocumentEditView = App.BeechDocumentDocumentView.extend();
	App.BeechDocumentAdministrationDocumentDeleteView = App.BeechDocumentDocumentView.extend();

}).call(this);