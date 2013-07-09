(function() {
	'use strict';
	App.BeechPartyAccountController = Ember.Controller.extend({
		url: '',
		loadUrl: function(params) {
			App.ModuleHandler.loadUrl(this.get('url') + params);
		}
	});
	// Front
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