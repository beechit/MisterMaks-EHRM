(function() {
	'use strict';
	App.PersonModuleViewMixin = Ember.View.extend(App.AjaxModuleViewMixin, {
		didInsertElement: function() {/* override default */}
	});
	App.AdministrationPersonModuleView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.personModule
	});

	App.AdministrationPersonModuleShowView = App.PersonModuleViewMixin.extend();
	App.AdministrationPersonModuleNewView = App.PersonModuleViewMixin.extend();
	App.AdministrationPersonModuleEditView = App.PersonModuleViewMixin.extend();
	App.AdministrationPersonModuleDeleteView = App.PersonModuleViewMixin.extend();

}).call(this);