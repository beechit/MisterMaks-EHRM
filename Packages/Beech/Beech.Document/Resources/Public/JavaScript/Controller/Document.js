(function() {
	'use strict';
	App.BeechDocumentDocumentController = Ember.Controller.extend({
		url: '',
		loadUrl: function(params) {
			App.ModuleHandler.loadUrl(this.get('url') + params);
		}
	});
	// Front
	App.BeechDocumentDocumentIndexController = App.BeechDocumentDocumentController.extend({
		url: MM.url.module.BeechDocumentDocumentIndex
	});
	App.BeechDocumentDocumentNewController = App.BeechDocumentDocumentController.extend({
		url: MM.url.module.BeechDocumentDocumentNew
	});
	App.BeechDocumentDocumentShowController = App.BeechDocumentDocumentController.extend({
		url: MM.url.module.BeechDocumentDocumentShow
	});
	App.BeechDocumentDocumentEditController = App.BeechDocumentDocumentController.extend({
		url: MM.url.module.BeechDocumentDocumentEdit
	});
	App.BeechDocumentDocumentDeleteController = App.BeechDocumentDocumentController.extend({
		url: MM.url.module.BeechDocumentDocumentDelete
	});
	App.BeechDocumentDocumentChartController = App.BeechDocumentDocumentController.extend({
		url: MM.url.module.BeechDocumentDocumentChart
	});
	// Administration
	App.BeechDocumentAdministrationDocumentIndexController = App.BeechDocumentDocumentController.extend({
		url: MM.url.module.BeechDocumentAdministrationDocumentIndex
	});
	App.BeechDocumentAdministrationDocumentNewController = App.BeechDocumentDocumentController.extend({
		url: MM.url.module.BeechDocumentAdministrationDocumentNew
	});
	App.BeechDocumentAdministrationDocumentShowController = App.BeechDocumentDocumentController.extend({
		url: MM.url.module.BeechDocumentAdministrationDocumentShow
	});
	App.BeechDocumentAdministrationDocumentEditController = App.BeechDocumentDocumentController.extend({
		url: MM.url.module.BeechDocumentAdministrationDocumentEdit
	});
	App.BeechDocumentAdministrationDocumentDeleteController = App.BeechDocumentDocumentController.extend({
		url: MM.url.module.BeechDocumentAdministrationDocumentDelete
	});

}).call(this);