(function() {
	'use strict';
	App.BeechPartyAdministrationCompanyController = Ember.Controller.extend({
		url: '',
		loadUrl: function(params) {
			App.ModuleHandler.loadUrl(this.get('url') + params);
		}
	});
	App.BeechPartyAdministrationCompanyIndexController = App.BeechPartyAdministrationCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyIndex
	});
	App.BeechPartyAdministrationCompanyNewController = App.BeechPartyAdministrationCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyNew
	});
	App.BeechPartyAdministrationCompanyShowController = App.BeechPartyAdministrationCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyShow
	});
	App.BeechPartyAdministrationCompanyEditController = App.BeechPartyAdministrationCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyEdit
	});
	App.BeechPartyAdministrationCompanyDeleteController = App.BeechPartyAdministrationCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyDelete
	});
}).call(this);