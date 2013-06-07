(function() {
	'use strict';
	App.BeechPartyCompanyController = Ember.Controller.extend({
		url: '',
		loadUrl: function(params) {
			App.ModuleHandler.loadUrl(this.get('url') + params);
		}
	});
	// Front
	App.BeechPartyCompanyIndexController = App.BeechPartyCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyIndex
	});
	App.BeechPartyCompanyNewController = App.BeechPartyCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyNew
	});
	App.BeechPartyCompanyShowController = App.BeechPartyCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyShow
	});
	App.BeechPartyCompanyEditController = App.BeechPartyCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyEdit
	});
	App.BeechPartyCompanyDeleteController = App.BeechPartyCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyDelete
	});

	// Administration
	App.BeechPartyAdministrationCompanyIndexController = App.BeechPartyCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyIndex
	});
	App.BeechPartyAdministrationCompanyNewController = App.BeechPartyCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyNew
	});
	App.BeechPartyAdministrationCompanyShowController = App.BeechPartyCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyShow
	});
	App.BeechPartyAdministrationCompanyEditController = App.BeechPartyCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyEdit
	});
	App.BeechPartyAdministrationCompanyDeleteController = App.BeechPartyCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyDelete
	});

}).call(this);