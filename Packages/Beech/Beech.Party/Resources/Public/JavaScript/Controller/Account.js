(function() {
	'use strict';

	// Front
	App.BeechPartyAccountController = App.ModuleHandlerAjaxController.extend();
	App.BeechPartyAccountIndexController = App.BeechPartyAccountController.extend({
		url: MM.url.module.BeechPartyAccountIndex
	});
	App.BeechPartyAccountNewController = App.BeechPartyAccountController.extend({
		url: MM.url.module.BeechPartyAccountNew
	});
	App.BeechPartyAccountShowController = App.BeechPartyAccountController.extend({
		url: MM.url.module.BeechPartyAccountShow
	});
	App.BeechPartyAccountEditController = App.BeechPartyAccountController.extend({
		url: MM.url.module.BeechPartyAccountEdit
	});
	App.BeechPartyAccountDeleteController = App.BeechPartyAccountController.extend({
		url: MM.url.module.BeechPartyAccountDelete
	});

	// Administration
	App.BeechPartyAdministrationAccountController = App.BeechPartyAccountController.extend();
	App.BeechPartyAdministrationAccountIndexController = App.BeechPartyAccountController.extend({
		url: MM.url.module.BeechPartyAdministrationAccountIndex
	});
	App.BeechPartyAdministrationAccountNewController = App.BeechPartyAccountController.extend({
		url: MM.url.module.BeechPartyAdministrationAccountNew
	});
	App.BeechPartyAdministrationAccountShowController = App.BeechPartyAccountController.extend({
		url: MM.url.module.BeechPartyAdministrationAccountShow
	});
	App.BeechPartyAdministrationAccountEditController = App.BeechPartyAccountController.extend({
		url: MM.url.module.BeechPartyAdministrationAccountEdit
	});
	App.BeechPartyAdministrationAccountDeleteController = App.BeechPartyAccountController.extend({
		url: MM.url.module.BeechPartyAdministrationAccountDelete
	});

}).call(this);