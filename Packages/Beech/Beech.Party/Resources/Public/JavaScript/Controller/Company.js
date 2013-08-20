(function() {
	'use strict';

	// Front
	App.BeechPartyCompanyController = App.ModuleHandlerAjaxController.extend();
	App.BeechPartyCompanyIndexController = App.BeechPartyCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyIndex
	});
	App.BeechPartyCompanyNewController = App.BeechPartyCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyNew
	});
	App.BeechPartyCompanyRefreshNewController = App.BeechPartyCompanyController.extend({
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
	App.BeechPartyAdministrationCompanyController = App.BeechPartyCompanyController.extend();
	App.BeechPartyAdministrationCompanyIndexController = App.BeechPartyCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyIndex
	});
	App.BeechPartyAdministrationCompanyNewController = App.BeechPartyCompanyController.extend({
		url: MM.url.module.BeechPartyAdministrationCompanyNew
	});
	App.BeechPartyAdministrationCompanyRefreshNewController = App.BeechPartyCompanyController.extend({
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