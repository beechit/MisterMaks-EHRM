(function() {
	'use strict';

	// Front
	App.BeechPartyPersonController = App.ModuleHandlerAjaxController.extend();
	App.BeechPartyPersonIndexController = App.BeechPartyPersonController.extend({
		url: MM.url.module.BeechPartyPersonIndex
	});
	App.BeechPartyPersonNewController = App.BeechPartyPersonController.extend({
		url: MM.url.module.BeechPartyPersonNew
	});
	App.BeechPartyPersonRefreshNewController = App.BeechPartyPersonController.extend({
		url: MM.url.module.BeechPartyPersonNew
	});
	App.BeechPartyPersonShowController = App.BeechPartyPersonController.extend({
		url: MM.url.module.BeechPartyPersonShow
	});
	App.BeechPartyPersonEditController = App.BeechPartyPersonController.extend({
		url: MM.url.module.BeechPartyPersonEdit
	});
	App.BeechPartyPersonDeleteController = App.BeechPartyPersonController.extend({
		url: MM.url.module.BeechPartyPersonDelete
	});

	// Administration
	App.BeechPartyAdministrationPersonController = App.BeechPartyPersonController.extend();
	App.BeechPartyAdministrationPersonIndexController = App.BeechPartyPersonController.extend({
		url: MM.url.module.BeechPartyAdministrationPersonIndex
	});
	App.BeechPartyAdministrationPersonNewController = App.BeechPartyPersonController.extend({
		url: MM.url.module.BeechPartyAdministrationPersonNew
	});
	App.BeechPartyAdministrationPersonRefreshNewController = App.BeechPartyPersonController.extend({
		url: MM.url.module.BeechPartyAdministrationPersonNew
	});
	App.BeechPartyAdministrationPersonShowController = App.BeechPartyPersonController.extend({
		url: MM.url.module.BeechPartyAdministrationPersonShow
	});
	App.BeechPartyAdministrationPersonEditController = App.BeechPartyPersonController.extend({
		url: MM.url.module.BeechPartyAdministrationPersonEdit
	});
	App.BeechPartyAdministrationPersonDeleteController = App.BeechPartyPersonController.extend({
		url: MM.url.module.BeechPartyAdministrationPersonDelete
	});

}).call(this);