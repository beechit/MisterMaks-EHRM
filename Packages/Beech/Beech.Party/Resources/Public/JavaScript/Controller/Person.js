(function() {
	'use strict';
	App.BeechPartyPersonController = Ember.Controller.extend({
		url: '',
		loadUrl: function(params) {
			App.ModuleHandler.loadUrl(this.get('url') + params);
		}
	});
	// Front
	App.BeechPartyPersonIndexController = App.BeechPartyPersonController.extend({
		url: MM.url.module.BeechPartyPersonIndex
	});
	App.BeechPartyPersonNewController = App.BeechPartyPersonController.extend({
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
	App.BeechPartyAdministrationPersonIndexController = App.BeechPartyPersonController.extend({
		url: MM.url.module.BeechPartyAdministrationPersonIndex
	});
	App.BeechPartyAdministrationPersonNewController = App.BeechPartyPersonController.extend({
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