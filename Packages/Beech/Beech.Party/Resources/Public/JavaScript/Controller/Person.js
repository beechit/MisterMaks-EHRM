(function() {
	'use strict';
	App.PersonModuleController = Ember.Controller.extend({
		url: '',
		loadUrl: function(params) {
			App.ModuleHandler.loadUrl(this.get('url') + params);
		}
	});

	App.AdministrationPersonModuleNewController = App.PersonModuleController.extend({
		url: MM.url.module.personModuleNew
	});
	App.AdministrationPersonModuleShowController = App.PersonModuleController.extend({
		url: MM.url.module.personModuleShow
	});
	App.AdministrationPersonModuleEditController = App.PersonModuleController.extend({
		url: MM.url.module.personModuleEdit
	});
	App.AdministrationPersonModuleDeleteController = App.PersonModuleController.extend({
		url: MM.url.module.personModuleDelete
	});
}).call(this);