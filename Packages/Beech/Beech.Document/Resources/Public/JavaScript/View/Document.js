(function() {
	'use strict';
	App.BeechDocumentDocumentViewMixin = Ember.View.extend(App.AjaxModuleViewMixin, {
		didInsertElement: function() {/* override default */}
	});

	// Front
	App.BeechDocumentDocumentIndexView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.BeechDocumentDocumentIndex
	});
	App.BeechDocumentDocumentShowView = App.BeechDocumentDocumentViewMixin.extend();
	App.BeechDocumentDocumentNewView = App.BeechDocumentDocumentViewMixin.extend();
	App.BeechDocumentDocumentEditView = App.BeechDocumentDocumentViewMixin.extend();
	App.BeechDocumentDocumentDeleteView = App.BeechDocumentDocumentViewMixin.extend();

	// Administration
	App.BeechDocumentAdministrationDocumentIndexView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.BeechDocumentAdministrationDocumentIndex
	});
	App.BeechDocumentAdministrationDocumentShowView = App.BeechDocumentDocumentViewMixin.extend();
	App.BeechDocumentAdministrationDocumentNewView = App.BeechDocumentDocumentViewMixin.extend();
	App.BeechDocumentAdministrationDocumentEditView = App.BeechDocumentDocumentViewMixin.extend();
	App.BeechDocumentAdministrationDocumentDeleteView = App.BeechDocumentDocumentViewMixin.extend();

}).call(this);